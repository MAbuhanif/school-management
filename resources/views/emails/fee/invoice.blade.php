<x-mail::message>
# Fee Invoice

Dear {{ $fee->student->user->name }},

A new fee invoice has been generated for you.

**Invoice Details:**
- **Amount:** ${{ number_format($fee->amount, 2) }}
- **Due Date:** {{ $fee->due_date->format('F j, Y') }}
- **Type:** {{ ucfirst($fee->type) }}

<x-mail::button :url="config('app.url')">
Pay Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
