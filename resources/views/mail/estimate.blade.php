<!DOCTYPE html>
<html lang="en">

<body>
    <p>Building Type: {{ $data['building_type'] ?? '' }} </p>
    <p>Steep Roof: {{ $data['steep_roof'] ?? '' }} </p>
    <p>Currently Roof: {{ $data['currently_roof'] ?? '' }} </p>
    <p>Installed Roof: {{ $data['installed_roof'] ?? '' }} </p>
    @if(!empty($data['type']) && $data['type'] == 'proposal' ) <p>Proposal Roof: {{ $data['proposal_roof'] ?? '' }} </p> @endif
    <p>When Start: {{ $data['when_start'] ?? '' }} </p>
    <p>Interested Financing: {{ $data['interested_financing'] ?? '' }} </p>
    <p>Address: {{ $data['address'] ?? '' }} </p>
    <p>Roof Size: {{ $data['roof_size'] ?? '' }} </p>
    <p>Installed Roof Price: {{ $data['installed_roof_price'] ?? '' }} </p>
    @if(!empty($data['type']) && $data['type'] == 'proposal' ) <p>Proposal Roof Price: {{ $data['proposal_roof_price'] ?? '' }} </p> @endif
    @if(!empty($data['type']) && $data['type'] == 'proposal' ) <p>Proposal Roof Description: {{ $data['proposal_roof_desc'] ?? '' }} </p> @endif
    <p>Installed Roof Description: {{ $data['installed_roof_desc'] ?? '' }} </p>
    <p>About: {{ $data['about'] ?? '' }} </p>
    <p>Name: {{ $data['name'] ?? '' }} </p>
    <p>Email: {{ $data['email'] ?? '' }} </p>
    <p>Phone: {{ $data['phone'] ?? '' }} </p>
</body>

</html>
