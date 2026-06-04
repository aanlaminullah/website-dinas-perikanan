<div>
    <label class="block text-sm font-medium text-heading mb-1">Nama <span class="text-danger">*</span></label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        placeholder="Nama lengkap" required />
    @error('name')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Email <span class="text-danger">*</span></label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        placeholder="alamat@email.com" required />
    @error('email')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">NIP <span class="text-danger">*</span></label>
    <input type="text" name="nip" value="{{ old('nip', $user->nip ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        placeholder="Nomor Induk Pegawai" required />
    @error('nip')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Role <span class="text-danger">*</span></label>
    <select name="role"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white"
        required>
        <option value="user" {{ old('role', $user->role ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ old('role', $user->role ?? 'user') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('role')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
