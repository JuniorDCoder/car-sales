<x-mail::message>
# New Contact Enquiry

You have received a new message from the AutoElite website.

- **Name:** {{ $data['name'] }}
- **Email:** {{ $data['email'] }}
- **Phone:** {{ $data['phone'] ?: 'N/A' }}

**Message**

{{ $data['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
