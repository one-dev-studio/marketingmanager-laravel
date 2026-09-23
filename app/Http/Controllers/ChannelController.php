<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChannelSetting;
use App\Models\InfluencerChannel;
use App\Models\SocialConnection;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index(Request $request, string $organizationId)
    {
        $channels = Channel::where('organization_id', $organizationId)
            ->with('socialConnection', 'settings')
            ->orderBy('display_name')
            ->get();

        if ($this->wantsJson($request)) {
            return response()->json($channels);
        }

        return view('channels.index', [
            'title' => 'Channels',
            'organizationId' => $organizationId,
            'channels' => $channels,
            'oauthPlatforms' => ['facebook', 'instagram', 'linkedin', 'twitter', 'tiktok', 'pinterest'],
        ]);
    }

    public function create(Request $request, string $organizationId)
    {
        return view('channels.create', [
            'title' => 'Add Channel',
            'organizationId' => $organizationId,
            'channel' => null,
        ]);
    }

    public function store(Request $request, string $organizationId)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'type' => 'required|in:email,whatsapp,amplify,paid_ads,press_release,influencer,social',
            'platform' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,disconnected',
            'settings' => 'nullable|array',
            'follower_count' => 'nullable|integer|min:0',
            'engagement_rate' => 'nullable|numeric|min:0|max:100',
            'influencer_name' => 'nullable|string|max:255',
            'handle' => 'nullable|string|max:255',
        ]);

        $channel = Channel::create([
            'organization_id' => $organizationId,
            'display_name' => $validated['display_name'],
            'type' => $validated['type'],
            'platform' => $validated['platform'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        $settings = $validated['settings'] ?? [];
        if ($channel->type === 'influencer') {
            $settings['follower_count'] = $validated['follower_count'] ?? 0;
            $settings['engagement_rate'] = $validated['engagement_rate'] ?? 0;
            $settings['handle'] = $validated['handle'] ?? null;

            InfluencerChannel::create([
                'organization_id' => $organizationId,
                'influencer_name' => $validated['influencer_name'] ?? $channel->display_name,
                'platform' => $channel->platform ?? 'other',
                'handle' => $validated['handle'] ?? null,
                'follower_count' => $validated['follower_count'] ?? 0,
                'engagement_rate' => $validated['engagement_rate'] ?? 0,
                'status' => 'active',
            ]);
        }

        if (!empty($settings)) {
            ChannelSetting::create([
                'channel_id' => $channel->id,
                'settings_json' => $settings,
            ]);
        }

        if ($this->wantsJson($request)) {
            return response()->json($channel->load('settings'), 201);
        }

        return redirect()
            ->route('main.social.channels.index', ['organizationId' => $organizationId])
            ->with('success', 'Channel created.');
    }

    public function show(Request $request, string $organizationId, Channel $channel)
    {
        $this->authorize('view', $channel);
        $channel->load('socialConnection', 'settings');

        if ($this->wantsJson($request)) {
            return response()->json($channel);
        }

        return view('channels.edit', [
            'title' => $channel->display_name,
            'organizationId' => $organizationId,
            'channel' => $channel,
        ]);
    }

    public function edit(Request $request, string $organizationId, Channel $channel)
    {
        $this->authorize('update', $channel);
        $channel->load('socialConnection', 'settings');

        return view('channels.edit', [
            'title' => 'Edit Channel',
            'organizationId' => $organizationId,
            'channel' => $channel,
        ]);
    }

    public function update(Request $request, string $organizationId, Channel $channel)
    {
        $this->authorize('update', $channel);

        $validated = $request->validate([
            'display_name' => 'sometimes|required|string|max:255',
            'platform' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:active,inactive,disconnected',
            'settings' => 'sometimes|nullable|array',
            'follower_count' => 'nullable|integer|min:0',
            'engagement_rate' => 'nullable|numeric|min:0|max:100',
            'handle' => 'nullable|string|max:255',
        ]);

        $channel->update([
            'display_name' => $validated['display_name'] ?? $channel->display_name,
            'platform' => $validated['platform'] ?? $channel->platform,
            'status' => $validated['status'] ?? $channel->status,
        ]);

        $settings = $validated['settings'] ?? ($channel->settings?->settings_json ?? []);
        if ($channel->type === 'influencer') {
            $settings['follower_count'] = $validated['follower_count'] ?? ($settings['follower_count'] ?? 0);
            $settings['engagement_rate'] = $validated['engagement_rate'] ?? ($settings['engagement_rate'] ?? 0);
            $settings['handle'] = $validated['handle'] ?? ($settings['handle'] ?? null);
        }

        if (array_key_exists('settings', $validated) || $channel->type === 'influencer') {
            $setting = $channel->settings;
            if ($setting) {
                $setting->update(['settings_json' => $settings]);
            } else {
                ChannelSetting::create([
                    'channel_id' => $channel->id,
                    'settings_json' => $settings,
                ]);
            }
        }

        if ($this->wantsJson($request)) {
            return response()->json($channel->load('settings'));
        }

        return redirect()
            ->route('main.social.channels.index', ['organizationId' => $organizationId])
            ->with('success', 'Channel updated.');
    }

    public function destroy(Request $request, string $organizationId, Channel $channel)
    {
        $this->authorize('delete', $channel);
        $channel->delete();

        if ($this->wantsJson($request)) {
            return response()->json(['message' => 'Channel deleted successfully']);
        }

        return redirect()
            ->route('main.social.channels.index', ['organizationId' => $organizationId])
            ->with('success', 'Channel deleted.');
    }

    public function updateSettings(Request $request, string $organizationId, Channel $channel)
    {
        $this->authorize('update', $channel);

        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        $setting = $channel->settings;
        if ($setting) {
            $setting->update(['settings_json' => $validated['settings']]);
        } else {
            ChannelSetting::create([
                'channel_id' => $channel->id,
                'settings_json' => $validated['settings'],
            ]);
        }

        if ($this->wantsJson($request)) {
            return response()->json(['message' => 'Settings updated successfully']);
        }

        return back()->with('success', 'Settings updated.');
    }

    public function testConnection(Request $request, string $organizationId, Channel $channel)
    {
        $this->authorize('view', $channel);

        $channel->load('socialConnection');
        $connection = $channel->socialConnection;

        $ok = false;
        $message = 'No social connection linked.';

        if ($connection instanceof SocialConnection) {
            $ok = $connection->isConnected();
            $message = $ok
                ? 'Connection is active.'
                : ($connection->error_message ?: 'Connection is not active.');
        } elseif (in_array($channel->type, ['email', 'whatsapp', 'amplify', 'paid_ads', 'press_release'], true)) {
            $settings = $channel->settings?->settings_json ?? [];
            $ok = !empty($settings);
            $message = $ok ? 'Channel settings are present.' : 'Add posting defaults or credentials first.';
        } elseif ($channel->type === 'influencer') {
            $ok = true;
            $message = 'Influencer channel does not require OAuth.';
        }

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => $ok,
                'message' => $message,
                'status' => $channel->status,
            ]);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }
}
