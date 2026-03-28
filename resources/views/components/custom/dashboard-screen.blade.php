@props(['dashboardProps'])
@php
  // dd($dashboardProps);
  use Illuminate\Support\Facades\Auth;
  $user = Auth::user();
  $person = $user->person;
  $statusColor = [
    'new' => 'text-blue-400',
    'in progress' => 'text-yellow-400',
    'completed' => 'text-green-400',
  ];
@endphp
<div class="min-h-screen bg-slate-950 text-slate-200 p-6 m-3 rounded-lg border border-blue-400">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-white">AutoLicence</h1>
            <p class="text-slate-400 text-sm">Driving Licence Management System</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="overflow-hidden w-30 h-30 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold shadow">
              <img src="{{ $person['image_path'] }}" alt="">
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Total Licences</p>
            <h2 class="text-2xl font-bold text-white mt-2">{{ $dashboardProps['totalLicences'] }}</h2>
        </div>

        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Pending Applications</p>
            <h2 class="text-2xl font-bold text-blue-500 mt-2">{{ $dashboardProps['pendingApplications'] }}</h2>
        </div>

        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Expired Licences</p>
            <h2 class="text-2xl font-bold text-red-400 mt-2">{{ $dashboardProps['expiredLicences'] }}</h2>
        </div>

        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Renewals This Month</p>
            <h2 class="text-2xl font-bold text-green-400 mt-2">{{ $dashboardProps['renewalsThisMonth'] }}</h2>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Applications -->
        <div class="lg:col-span-2 bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Applications</h2>
                <a href="{{ route('applications.index') }}" class="text-sm text-blue-500 hover:underline">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-400 border-b border-slate-800">
                            <th class="py-2">Name</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-300">
                      @foreach ($dashboardProps['recentApplications'] as $recent)
                        <tr class="border-b border-slate-800">
                          <td class="py-3">{{ $recent['person']['name'] }}</td>
                          <td><span class="{{ $statusColor[$recent['status']] }}">{{ $recent['status'] }}</span></td>
                          <td>{{ $recent['created_at'] }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>

            <div class="flex flex-col gap-3">
                <a  href="{{ route('localLicence.create') }}"
                    class="text-center bg-slate-800 hover:bg-slate-700 transition rounded-xl py-2 text-sm font-medium shadow">
                    Add New Licence
                </a>
                <a 
                type="submit"
                href="{{ route('person.index') }}"
                class="text-center bg-slate-800 hover:bg-slate-700 transition rounded-xl py-2 text-sm font-medium">
                    Review Applicants
                </a>
                <a 
                type="submit"
                href="{{ route('localLicence.show') }}"
                class="text-center bg-slate-800 hover:bg-slate-700 transition rounded-xl p-3 text-sm font-medium">
                    Local licence application details
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-10 text-center text-xs text-slate-500">
        © 2026 AutoLicence
    </div>
</div>
