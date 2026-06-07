@component('mail::message')
# Response to Your Inquiry

Hello {{ $contact->name }},

Thank you for reaching out to the PWD Employment Portal. We're pleased to provide a response to your inquiry.

**Inquiry Type:** {{ $contact->inquiry_type_display }}

**Your Original Message:** "{{ $contact->subject }}"

---

## Our Response:

{{ $contact->response_notes }}

---

@component('mail::button', ['url' => route('contact-messages.show', $contact->id)])
View Your Message in Dashboard
@endcomponent

You can view all your messages and responses in your PWD dashboard anytime.

Best regards,
**PWD Employment Support Team**
@endcomponent
