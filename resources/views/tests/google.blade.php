{
"kind": "calendar#events",
"etag": "\"1451154074172000\"",
"summary": "Recording Cowbell",
"description": "",
"updated": "{{ \Carbon\Carbon::now()->format('Y-m-dTH:00:00+01:00') }}",
"timeZone": "Europe/Stockholm",
"accessRole": "reader",
"defaultReminders": [],
"items": [
@isset($events)
    @foreach($events as $event)
        {
            "kind": "calendar#event",
            "etag": "\"2902308148132000\"",
            "id": "UNIQUE_UID",
            "status": "confirmed",
            "htmlLink": "https://calendar.google.com/calendar/event?eid=UNIQUE_EID",
            "created": "{{ \Carbon\Carbon::now()->format('Y-m-dTH:00:00+01:00') }}",
            "updated": "{{ \Carbon\Carbon::now()->format('Y-m-dTH:00:00+01:00') }}",
            "summary": "{{ $event->title }}",
            "description": "{{ $event->description }}",
            "location": "{{ $event->location }}",
            "creator": {
                "email": "default@departur.se"
            },
            "organizer": {
                "email": "bruce.dickinson@group.calendar.google.com",
                "displayName": "Recording Cowbell",
                "self": true
            },
            "start": {
                "dateTime": "{{ $event->start_time->format('Y-m-dTH:00:00+01:00') }}"
            },
            "end": {
                "dateTime": "{{ $event->end_time->format('Y-m-dTH:00:00+01:00') }}"
            },
            "iCalUID": "UNIQUE_UID@google.com",
            "sequence": 2
        }
        @unless($loop->last)
            ,
        @endif
    @endforeach
@endisset
]
}