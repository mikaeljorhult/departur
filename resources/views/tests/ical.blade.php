BEGIN:VCALENDAR
PRODID:-//Google Inc//Google Calendar 70.9054//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:Recording Cowbell
X-WR-TIMEZONE:Europe/Stockholm
X-WR-CALDESC:
@isset($events)
@foreach($events as $event)
BEGIN:VEVENT
DTSTART:{{ $event->start_time->format('Ymd\TH0000\Z') }}
DTEND:{{ $event->end_time->format('Ymd\TH0000\Z') }}
DTSTAMP:{{ $event->start_time->format('Ymd\TH0000\Z') }}
UID:UNIQUE_UID@google.com
CREATED:{{ \Carbon\Carbon::now()->format('Ymd\TH0000\Z') }}
DESCRIPTION:{{ $event->description }}
LAST-MODIFIED:{{ \Carbon\Carbon::now()->format('Ymd\TH0000\Z') }}
LOCATION:{{ $event->location }}
SEQUENCE:2
STATUS:CONFIRMED
SUMMARY:{{ $event->title }}
TRANSP:OPAQUE
END:VEVENT
@endforeach
@endisset
END:VCALENDAR