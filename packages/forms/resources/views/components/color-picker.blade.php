@php
    $affixLabelClasses = [
        'whitespace-nowrap group-focus-within:text-primary-500',
        'text-gray-400' => ! $errors->has($getStatePath()),
        'text-danger-400' => $errors->has($getStatePath()),
    ];
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div {{ $attributes->merge($getExtraAttributes())->class(['flex items-center space-x-1 rtl:space-x-reverse group filament-forms-color-picker-component']) }}>
        {{ $getPrefixAction() }}

        @if ($label = $getPrefixLabel())
            <span @class($affixLabelClasses)>
                {{ $label }}
            </span>
        @endif

        <div
            x-data="colorPickerFormComponent({
                isAutofocused: @js($isAutofocused()),
                isDisabled: @js($isDisabled()),
                state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }}
            })"
            x-on:click="openPicker()"
            x-on:click.away="closePicker()"
            x-on:keydown.escape.stop="closePicker()"
            {{ $getExtraAlpineAttributeBag()->class(['relative flex-1']) }}
        >
            <input
                x-ref="input"
                x-on:focus="openPicker()"
                type="text"
                dusk="filament.forms.{{ $getStatePath() }}"
                id="{{ $getId() }}"
                autocomplete="off"
                {!! $isDisabled() ? 'disabled' : null !!}
                {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                @if (! $isConcealed())
                    {!! $isRequired() ? 'required' : null !!}
                @endif
                {{ $getExtraInputAttributeBag()->class([
                    'text-gray-900 block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70',
                    'dark:bg-gray-700 dark:text-white' => config('forms.dark_mode'),
                    'border-gray-300' => ! $errors->has($getStatePath()),
                    'dark:border-gray-600' => (! $errors->has($getStatePath())) && config('forms.dark_mode'),
                    'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                ]) }}
            />

            <span
                x-cloak
                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none rtl:right-auto rtl:left-0 rtl:pl-2"
            >
                <span
                    x-bind:style="{'background-color': state}"
                    class="relative overflow-hidden rounded-md w-7 h-7 filament-forms-color-picker-component-preview"
                ></span>
            </span>

            <{{ match($getFormat()) {
                'hsl' => 'hsl-string',
                'rgb' => 'rgb-string',
                'rgba' => 'rgba-string',
                default => 'hex',
            } }}-color-picker
                x-cloak
                x-ref="picker"
                x-show="isOpen"
                x-on:blur="isOpen && closePicker()"
                x-transition:leave="ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @class([
                    'mt-4 absolute z-10 shadow-lg',
                    'opacity-70 pointer-events-none' => $isDisabled(),
                ])
            />
        </div>

        @if ($label = $getSuffixLabel())
            <span @class($affixLabelClasses)>
                {{ $label }}
            </span>
        @endif

        {{ $getSuffixAction() }}
    </div>
</x-dynamic-component>
