<div 
    x-data="
    { 
      user: null,
      get formAction() { return `/users/${this.user?.id}/permissions` },
      permissions: [
        { name: 'View',   value: 1, enabled: false },
        { name: 'Create', value: 2, enabled: false },
        { name: 'Edit',   value: 4, enabled: false },
        { name: 'Delete', value: 8, enabled: false },
      ],

      isAdmin: false, 

      get total() {
        if (this.isAdmin) return 15;
        return this.permissions
          .filter(p => p.enabled)
          .reduce((sum, p) => sum + p.value, 0);
      },

      toggleAdmin() {
        if (this.isAdmin) {
          this.permissions.forEach(p => p.enabled = true);
        } else {
          this.permissions.forEach(p => p.enabled = false);
        }
      },
    }"
    @user-id-updated.window = "user = event.detail;"
    class="flex">

    <div id="userDataContainer" class="w-75 bg-gray-900 border border-gray-700 rounded-lg p-6 m-5">
      <h1 class="text-xl font-semibold text-white mb-4">
        User Information
      </h1>
      <!-- User ID -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">User ID</p>
          <x-text-input readonly x-bind:value="user?.id" class="text-base text-white font-medium"/>
        </div>
        <!-- User Name -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">User Name</p>
          <x-text-input readonly x-bind:value="user?.name" class="text-base text-white font-medium"/>
        </div>
        <div class="mb-3">
          <p class="text-sm text-gray-400">Active</p>
          <x-text-input readonly x-bind:value="user?.isActive ? 'yes' : 'no'" class="text-base text-white font-medium"/>
        </div>
        <!-- Email -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">Email</p>
          <x-text-input readonly x-bind:value="user?.email" class="w-full text-base text-white font-medium"/>
        </div>
        <!-- Email -->
        <div class="mb-3">
          <p class="text-sm text-gray-400">Permissions</p>
          <x-text-input readonly x-bind:value="user?.permissions" class="text-base text-white font-medium">21 Years</x-text-input>
        </div>
    </div>

    <div id="permissions"
        class="w-100 bg-slate-900 border border-slate-800 rounded-2xl m-5 p-6 shadow-lg">
      <h2 class="text-xl font-semibold text-slate-100 mb-6">
          User <span class="text-cyan" x-text="user?.name"></span> Permissions
      </h2>
      <div class="space-y-5">
          <!-- Permission Row -->
      <form id="f" method="post" x-bind:action="formAction">
        @csrf
          <div class="flex items-center justify-between border-b border-slate-800 pb-3">
      
              <span class="text-slate-300 font-medium">
                Admin
              </span>
              <input type="hidden" name="user_id" x-bind:value="user?.id"/>
              <input type="hidden" name="newPermissions" x-bind:value="total"/>
              <div class="flex items-center gap-6">
      
                  <!-- Yes -->
                  <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio"
                             name="perm_admin"
                             value="1"
                             @change="isAdmin = true; toggleAdmin(); total"
                             class="w-4 h-4 text-blue-600 bg-slate-800 border-slate-600 focus:ring-blue-600">
                      <span class="text-slate-400">Yes</span>
                  </label>
                  <!-- No -->
                  <label class="flex items-center gap-2 cursor-pointer">
                      <input type="radio"
                             name="perm_admin"
                             value="0"
                             @change="isAdmin = false; toggleAdmin(); total"
                             checked
                             class="w-4 h-4 text-blue-600 bg-slate-800 border-slate-600 focus:ring-blue-600">
                      <span class="text-slate-400">No</span>
                  </label>
              </div>
          </div>
          <template x-for="perm in permissions" :key="perm.name">
            <div x-show="!isAdmin" class="flex items-center justify-between border-b border-slate-800 pb-3">
      
                <span class="text-slate-300 font-medium" x-text="perm.name"></span>
                <div class="flex items-center gap-6">
      
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio"
                              class="w-4 h-4 text-blue-600 bg-slate-800 border-slate-600 focus:ring-blue-600"
                              :name="'perm_' + perm.name"
                              value="1"
                              @change="perm.enabled = true; total">
      
                        <span class="text-slate-400">Yes</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio"
                              class="w-4 h-4 text-blue-600 bg-slate-800 border-slate-600 focus:ring-blue-600"
                              :name="'perm_' + perm.name"
                              value="0"
                              checked
                              @change="perm.enabled = false; total">
                        <span class="text-slate-400">No</span>
                    </label>
                </div>
            </div>
          </template>
          <x-input-error :messages="$errors->get('user_id')"/>
          <x-input-error :messages="$errors->get('newPermissions')"/>
        </div>
      </form>
      <!-- Save Button -->
      <div class="mt-6 flex justify-end">
          <button form="f" type="submit" class="px-5 py-2 rounded-lg bg-blue-700 hover:bg-blue-600 text-white font-medium transition">
              Save Permissions
          </button>
      </div>
    </div>

</div>