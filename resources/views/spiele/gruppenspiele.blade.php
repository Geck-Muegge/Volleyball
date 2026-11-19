<!-- resources/views/gruppenspielen.blade.php -->
@extends('layouts.app')

@section('title', 'Gruppenspielen')

@section('content')
<div class="container-main" style="padding-top: 40px;">
    <h1 style="font-size: 32px; font-weight: 700; color: #1D68AC; margin-bottom: 40px; text-align: center;">
        <i class="fas fa-users"></i> Gruppenspielen
    </h1>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        @foreach ($groups as $group)
            <div style="margin-bottom: 60px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #333; margin-bottom: 20px;">
                    {{ $group['name'] }}
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    @foreach ($group['matches'] as $match)
                        <x-match-card
                            :match="$match"
                            variant="group"
                            label="{{ $match['matchday'] ?? 'Spieltag' }}"
                        />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
