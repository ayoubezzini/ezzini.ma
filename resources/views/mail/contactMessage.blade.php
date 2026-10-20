{{ __('site.mail.intro') }}

{{ $senderName }} <{{ $senderEmail }}>
@if($company)
{{ $company }}
@endif
{{ strtoupper($visitorLocale) }}

---

{{ $messageBody }}
