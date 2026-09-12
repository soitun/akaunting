<x-loading.content />

<div class="relative mt-4">
    <x-form 
        id="{{ $formId }}"
        :route="$formRoute"
        method="{{ $formMethod }}"
        :model="$document"
    >
        @if (! $hideCompany)
            <x-documents.form.company :type="$type" />
        @endif

        <x-documents.form.main type="{{ $type }}" />

        @if ($showRecurring)
            <x-documents.form.recurring type="{{ $type }}" />
        @endif

        @if (! $hideAdvanced)
            <x-documents.form.advanced type="{{ $type }}" />
        @endif

        <x-form.input.hidden name="type" :value="old('type', $type)" v-model="form.type" />
        <x-form.input.hidden name="status" :value="old('status', $status)" v-model="form.status" />
        <x-form.input.hidden name="amount" :value="old('amount', '0')" v-model="form.amount" />

        @if (! empty($document))
            <div v-if="taxes_out_of_date || recalculate_taxes"
                class="w-full my-5 py-3 px-4 rounded-lg bg-orange-100 text-orange-700"
                role="alert"
            >
                <div class="font-bold text-sm">
                    {{ trans('documents.tax_rate.changed_title') }}
                </div>

                <div class="text-sm mt-1">
                    {{ trans('documents.tax_rate.changed_description') }}
                </div>

                <label class="flex items-center mt-3 text-sm font-medium cursor-pointer">
                    <input
                        type="checkbox"
                        v-model="recalculate_taxes"
                        class="rounded-sm text-purple border-gray-300 cursor-pointer focus:outline-none focus:ring-transparent ltr:mr-2 rtl:ml-2"
                    >
                    {{ trans('documents.tax_rate.recalculate') }}
                </label>
            </div>
        @endif

        @if (! $hideButtons)
            <x-documents.form.buttons :type="$type" />
        @endif
    </x-form>
</div>
