import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';
import { useToast } from '@/Components/Toast';
import { useState } from 'react';

export default function Index({ auth, invoices, stripeKey }) {
    const { addToast } = useToast();
    
    // Form for Pay Now (Action)
    const { post: postCheckout, processing: processingCheckout } = useForm();
    
    const handlePay = (invoiceId) => {
        postCheckout(route('payments.checkout', invoiceId), {
            onError: () => addToast('Failed to initiate payment.', 'error'),
        });
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Invoices & Payments</h2>}
        >
            <Head title="Payments" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            
                            {invoices.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="min-w-full bg-white border border-gray-200">
                                        <thead>
                                            <tr>
                                                <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    ID
                                                </th>
                                                <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Description
                                                </th>
                                                <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Amount ($)
                                                </th>
                                                <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Due Date
                                                </th>
                                                <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th className="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {invoices.map((invoice) => (
                                                <tr key={invoice.id}>
                                                    <td className="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
                                                        #{invoice.id}
                                                    </td>
                                                    <td className="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
                                                        {invoice.description}
                                                    </td>
                                                    <td className="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
                                                        {invoice.amount}
                                                    </td>
                                                    <td className="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">
                                                        {invoice.due_date}
                                                    </td>
                                                    <td className="px-6 py-4 border-b border-gray-200 text-sm">
                                                        <span
                                                            className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                                invoice.status === 'paid'
                                                                    ? 'bg-green-100 text-green-800'
                                                                    : 'bg-red-100 text-red-800'
                                                            }`}
                                                        >
                                                            {invoice.status.toUpperCase()}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4 border-b border-gray-200 text-sm font-medium">
                                                        {invoice.status === 'unpaid' && (
                                                            <PrimaryButton
                                                                onClick={() => handlePay(invoice.id)}
                                                                disabled={processingCheckout}
                                                                className="bg-indigo-600 hover:bg-indigo-700"
                                                            >
                                                                Pay Now
                                                            </PrimaryButton>
                                                        )}
                                                        {invoice.status === 'paid' && (
                                                            <span className="text-gray-500">Paid</span>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center py-10 text-gray-500">
                                    No invoices found.
                                </div>
                            )}

                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
