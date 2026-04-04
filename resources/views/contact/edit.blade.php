@extends('layouts.master_dashboard')

@section('content')
   
    
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.edit_contact') }}
                </h3>
                <a href="{{ route('contact.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.03 4.47a.75.75 0 0 1 0 1.06L5.81 11.25H20a.75.75 0 0 1 0 1.5H5.81l5.22 5.72a.75.75 0 1 1-1.06 1.06l-6.5-7a.75.75 0 0 1 0-1.06l6.5-7a.75.75 0 0 1 1.06 0Z"/>
                    </svg>
                    {{ __('messages.cancel') }}
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('contact.update', $contact) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="remove_image" id="remove_image" value="0">
                <div class="space-y-6">

                    <!-- Avatar Image -->
                    <div class="mb-8 flex justify-center">
                        <div class="relative">
                            <div class="h-32 w-32 rounded-full border-4 border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-800 overflow-hidden flex items-center justify-center transition-all" id="avatar-preview">
                                @if($contact->image)
                                    <img src="{{ \App\Helpers\ImageHelper::url($contact->image) }}" alt="{{ $contact->name }}" class="h-40 w-40 object-cover">
                                @else
                                    <svg class="h-16 w-16 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                    </svg>
                                @endif
                            </div>
                            <label for="image" class="absolute bottom-0 right-0 flex items-center justify-center h-10 w-10 rounded-full bg-brand-500 hover:bg-brand-600 cursor-pointer shadow-lg transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </label>
                            <!-- Upload Success Indicator -->
                            <div class="absolute -top-2 -right-2 h-8 w-8 rounded-full bg-success-500 text-white flex items-center justify-center shadow-lg hidden" id="upload-success-badge">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/*"
                                class="hidden"
                                onchange="previewImage(event)"
                            >
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1.5 text-center text-xs text-error-500">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-center">
                        <button type="button" onclick="removeImage()" class="inline-flex items-center gap-2 rounded-lg bg-error-500 px-4 py-2 text-sm font-medium text-white hover:bg-error-600 transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                            </svg>
                            {{ __('messages.remove') }}
                        </button>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.name') }} <span class="text-error-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $contact->name) }}"
                            placeholder="{{ __('messages.name') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                                {{ $errors->has('name') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.email') }} <span class="text-error-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $contact->email) }}"
                            placeholder="user@example.com"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                                {{ $errors->has('email') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.phone') }}
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $contact->phone) }}"
                            placeholder="+1 (555) 000-0000"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >
                        @error('phone')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birthday -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.birthday') }}
                        </label>
                        <div class="flex items-end gap-3">
                            <!-- Day -->
                            <div class="flex-1">
                                <label for="birthday_day" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-500">
                                    {{ __('messages.day') }}
                                </label>
                                <input
                                    type="number"
                                    id="birthday_day"
                                    name="birthday_day"
                                    min="1"
                                    max="31"
                                    value="{{ old('birthday_day', $contact->birthday?->format('d')) }}"
                                    placeholder="01"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                >
                            </div>
                            <!-- Month -->
                            <div class="flex-1">
                                <label for="birthday_month" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-500">
                                    {{ __('messages.month') }}
                                </label>
                                <select
                                    id="birthday_month"
                                    name="birthday_month"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                >
                                    <option value="">{{ __('messages.select') }}</option>
                                    @foreach(\App\Enums\Constants::months() as $value => $monthKey)
                                        <option value="{{ $value }}" {{ old('birthday_month', $contact->birthday?->format('m')) == $value ? 'selected' : '' }}>
                                            {{ __('messages.' . $monthKey) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Year (Optional) -->
                            <div class="flex-1">
                                <label for="birthday_year" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-500">
                                    {{ __('messages.year_optional') }}
                                </label>
                                <input
                                    type="number"
                                    id="birthday_year"
                                    name="birthday_year"
                                    min="1900"
                                    max="{{ now()->year }}"
                                    value="{{ old('birthday_year', $contact->birthday?->format('Y')) }}"
                                    placeholder="YYYY"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                >
                            </div>
                        </div>
                        @error('birthday')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                        @error('birthday_day')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                        @error('birthday_month')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                        @error('birthday_year')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.category') }}
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >
                            <option value="">{{ __('messages.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $contact->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.notes') }}
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            placeholder="{{ __('messages.notes') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >{{ old('notes', $contact->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gift Ideas -->
                    <div>
                        <label for="gift_ideas" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.gift_ideas') }}
                        </label>
                        <textarea
                            id="gift_ideas"
                            name="gift_ideas"
                            rows="4"
                            placeholder="{{ __('messages.gift_ideas') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >{{ old('gift_ideas', $contact->gift_ideas) }}</textarea>
                        @error('gift_ideas')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-2">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition-colors focus:outline-none focus:ring-3 focus:ring-brand-500/20"
                        >
                            {{ __('messages.save') }}
                        </button>
                        <a
                            href="{{ route('contact.index') }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            {{ __('messages.cancel') }}
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
 <script>
        // Define previewImage immediately to avoid timing issues with async modules
        window.previewImage = function(event) {
            const file = event.target.files[0];
            const successBadge = document.getElementById('upload-success-badge');
            const avatarPreview = document.getElementById('avatar-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="h-40 w-40 object-cover">';
                    // Show success badge
                    successBadge.classList.remove('hidden');
                    // Change border color to indicate success
                    avatarPreview.parentElement.classList.add('border-success-500', 'border-4');
                    avatarPreview.parentElement.classList.remove('border-gray-200', 'dark:border-gray-700');
                };
                reader.readAsDataURL(file);
            }
        };

           // Remove image handler
        window.removeImage = function() {
            const preview = document.getElementById('avatar-preview');
            const input = document.getElementById('image');
            const badge = document.getElementById('upload-success-badge');
            const removeImageInput = document.getElementById('remove_image');

            // Reset file input
            input.value = '';

            // Reset preview to default
            preview.innerHTML = `
                <svg class="h-16 w-16 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
            `;

            // Hide success badge
            badge.classList.add('hidden');

            // Set flag to remove image on backend
            removeImageInput.value = '1';
        };
    </script>
@endpush