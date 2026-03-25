<!-- resources/views/components/dashboard.blade.php -->
@php
  use Illuminate\Support\Facades\Auth;
  $user = Auth::user();
  $person = $user->person;
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
            <h2 class="text-2xl font-bold text-white mt-2">1,245</h2>
        </div>

        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Pending Applications</p>
            <h2 class="text-2xl font-bold text-blue-500 mt-2">87</h2>
        </div>

        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Expired Licences</p>
            <h2 class="text-2xl font-bold text-red-400 mt-2">32</h2>
        </div>

        <div class="bg-slate-900 p-5 rounded-2xl shadow-lg border border-slate-800">
            <p class="text-sm text-slate-400">Renewals This Month</p>
            <h2 class="text-2xl font-bold text-green-400 mt-2">54</h2>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Applications -->
        <div class="lg:col-span-2 bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-white">Recent Applications</h2>
                <button class="text-sm text-blue-500 hover:underline">View All</button>
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
                        <tr class="border-b border-slate-800">
                            <td class="py-3">John Doe</td>
                            <td><span class="text-yellow-400">Pending</span></td>
                            <td>2026-03-15</td>
                            <td class="text-right"><button class="text-blue-500">View</button></td>
                        </tr>
                        <tr class="border-b border-slate-800">
                            <td class="py-3">Jane Smith</td>
                            <td><span class="text-green-400">Approved</span></td>
                            <td>2026-03-14</td>
                            <td class="text-right"><button class="text-blue-500">View</button></td>
                        </tr>
                        <tr>
                            <td class="py-3">Ali Hassan</td>
                            <td><span class="text-red-400">Rejected</span></td>
                            <td>2026-03-13</td>
                            <td class="text-right"><button class="text-blue-500">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>

            <div class="flex flex-col gap-3">
                <a class="text-center bg-slate-800 hover:bg-slate-700 transition rounded-xl py-2 text-sm font-medium shadow">
                    Add New Licence
                </a>
                <a 
                type="submit"
                href="{{ route('applications.index') }}"
                class="text-center bg-slate-800 hover:bg-slate-700 transition rounded-xl py-2 text-sm font-medium">
                    Review Applications
                </a>
                <a class="text-center bg-slate-800 hover:bg-slate-700 transition rounded-xl py-2 text-sm font-medium">
                    Generate Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-10 text-center text-xs text-slate-500">
        © 2026 AutoLicence
    </div>
</div>
