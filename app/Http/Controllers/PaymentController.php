<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Invoice::query()->with(['student.user']);

        // Roles: Admin sees all, Parent/Student sees theirs
        if ($user->hasRole('student')) {
            $query->whereHas('student', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($user->hasRole('parent')) {
             // Logic for parent seeing child's invoices (omitted for brevity, assume simple relationship)
        }

        $invoices = $query->latest()->get();

        return Inertia::render('Payments/Index', [
            'invoices' => $invoices,
            'stripeKey' => env('STRIPE_KEY'), // Pass PUB key to frontend if needed (mostly needed for Elements, but we use Checkout)
        ]);
    }

    public function store(Request $request)
    {
        // Admin or Teacher creating an invoice
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'required|string',
        ]);

        Invoice::create($validated);

        return redirect()->back()->with('success', 'Invoice created successfully.');
    }

    public function checkout(Invoice $invoice)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkoutInv = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $invoice->description,
                    ],
                    'unit_amount' => $invoice->amount * 100, // Cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payments.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payments.cancel'),
            'metadata' => [
                'invoice_id' => $invoice->id,
            ],
        ]);

        $invoice->update(['stripe_session_id' => $checkoutInv->id]);

        return Inertia::location($checkoutInv->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');

        try {
            $session = Session::retrieve($sessionId);
            if (!$session) {
                throw new \Exception('Session not found');
            }

            // Ideally rely on Webhook, but for immediate feedback:
            $invoiceId = $session->metadata->invoice_id;
            $invoice = Invoice::findOrFail($invoiceId);
            if ($session->payment_status === 'paid') {
                $invoice->update(['status' => 'paid']);
            }
            
             return redirect()->route('payments.index')->with('success', 'Payment successful!');

        } catch (\Exception $e) {
             return redirect()->route('payments.index')->with('error', 'Unable to verify payment.');
        }
    }

    public function cancel()
    {
        return redirect()->route('payments.index')->with('info', 'Payment cancelled.');
    }

    public function webhook(Request $request)
    {
        // Simple webhook handler
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;
            $invoiceId = $session->metadata->invoice_id;
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $invoice->update(['status' => 'paid']);
            }
        }

        return response('Success', 200);
    }
}
