<x-mail::message>
# Nouvelle demande de contact

Une nouvelle demande de contact a été soumis

- Prénom : {{ $data['lastname']}}
- Nom : {{ $data['name']}}
- Email : {{ $data['email']}}

**Message :**<br>
{{ $data['message']}}
</x-mail::message>
