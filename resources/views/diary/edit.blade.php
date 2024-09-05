<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Diary Entry') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <h1 class="text-2xl font-bold mb-4">
                        {{ __('Hello, ') . Auth::user()->name . '!' }}
                    </h1>
                    <p class="mt-4">
                        <b>{{ __("Update Your Diary Entry") }}</b>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Form to edit the diary entry -->
                    <form method="POST" action="{{ route('diary.update', $diaryEntry) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" id="date" name="date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100"
                                value="{{ old('date', $diaryEntry->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                            <textarea id="content" name="content" rows="5"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100"
                                required>{{ old('content', $diaryEntry->content) }}</textarea>
                            @error('content')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emotion selection and intensity input -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select
                                Emotions</label>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach ($emotions as $emotion)
                                    <div class="flex items-center mb-4">
                                        <!-- Checkbox for each emotion -->
                                        <input type="checkbox" id="emotion_{{ $emotion->id }}" name="emotions[]"
                                            value="{{ $emotion->id }}"
                                            class="h-5 w-5 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-indigo-600"
                                            {{ in_array($emotion->id, old('emotions', $diaryEntry->emotions->pluck('id')->toArray())) ? 'checked' : '' }}
                                            onchange="toggleIntensityInput({{ $emotion->id }})">
                                        <label for="emotion_{{ $emotion->id }}"
                                            class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $emotion->name }}
                                        </label>

                                        <!-- Intensity input for selected emotions -->
                                        <div class="ml-4 {{ in_array($emotion->id, old('emotions', $diaryEntry->emotions->pluck('id')->toArray())) ? '' : 'hidden' }}"
                                            id="intensity_container_{{ $emotion->id }}">
                                            <input type="number" name="intensity[{{ $emotion->id }}]"
                                                class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="Intensity" min="1" max="10"
                                                value="{{ old('intensity.' . $emotion->id, $diaryEntry->emotions->find($emotion->id)->pivot->intensity ?? '') }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('emotions')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit button -->
                        <x-primary-button>{{ __('Update Entry') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <center>
        <x-secondary-button onclick="disableFormSubmissionAndGoBack()">
            {{ __('Back to Previous') }}
        </x-secondary-button>
    </center>

    <!-- Script to toggle visibility of intensity inputs -->
    <script>
        function toggleIntensityInput(emotionId) {
            var checkbox = document.getElementById('emotion_' + emotionId);
            var intensityContainer = document.getElementById('intensity_container_' + emotionId);
            if (checkbox.checked) {
                intensityContainer.classList.remove('hidden');
            } else {
                intensityContainer.classList.add('hidden');
            }
        }

        // Initialize visibility of intensity inputs based on current selection
        document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            toggleIntensityInput(checkbox.value);
        });

        function disableFormSubmissionAndGoBack() {
            window.onbeforeunload = null;
            window.history.back();
        }
    </script>
</x-app-layout>