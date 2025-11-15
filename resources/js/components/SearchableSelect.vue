<template>
  <div>
    <label v-if="label" class="label-field">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    <multiselect
      v-model="selectedValue"
      :options="options"
      :multiple="multiple"
      :searchable="true"
      :allow-empty="!required"
      :placeholder="placeholder || 'Select an option'"
      :disabled="disabled"
      :class="['multiselect-custom', { 'multiselect-error': error }]"
      :track-by="trackBy"
      :label="labelKey"
      @update:model-value="handleChange"
      @blur="$emit('blur')"
    >
      <template #noOptions>
        <span>{{ noOptionsText || 'No options available' }}</span>
      </template>
      <template #noResult>
        <span>{{ noResultText || 'No elements found. Consider changing the search query.' }}</span>
      </template>
    </multiselect>
    <span v-if="error" class="error-message">{{ error }}</span>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

const props = defineProps({
  modelValue: {
    type: [String, Number, Array, Object],
    default: null,
  },
  options: {
    type: Array,
    required: true,
    default: () => [],
  },
  label: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  required: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  trackBy: {
    type: String,
    default: 'id',
  },
  labelKey: {
    type: String,
    default: 'name',
  },
  noOptionsText: {
    type: String,
    default: '',
  },
  noResultText: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue', 'blur']);

const selectedValue = ref(props.modelValue);

const handleChange = (value) => {
  if (props.multiple) {
    // For multiple select, emit array of IDs
    const ids = value ? value.map(item => {
      return typeof item === 'object' ? item[props.trackBy] : item;
    }) : [];
    emit('update:modelValue', ids);
  } else {
    // For single select, emit the ID
    const id = value ? (typeof value === 'object' ? value[props.trackBy] : value) : null;
    emit('update:modelValue', id);
  }
};

watch(() => props.modelValue, (newValue) => {
  if (props.multiple) {
    // For multiple, find matching options
    if (Array.isArray(newValue) && newValue.length > 0) {
      selectedValue.value = props.options.filter(opt => {
        const optId = typeof opt === 'object' ? opt[props.trackBy] : opt;
        return newValue.includes(optId);
      });
    } else {
      selectedValue.value = [];
    }
  } else {
    // For single, find matching option
    if (newValue !== null && newValue !== '') {
      const found = props.options.find(opt => {
        const optId = typeof opt === 'object' ? opt[props.trackBy] : opt;
        return optId === newValue;
      });
      selectedValue.value = found || null;
    } else {
      selectedValue.value = null;
    }
  }
}, { immediate: true });

watch(() => props.options, () => {
  // Re-sync when options change
  if (props.multiple) {
    if (Array.isArray(props.modelValue) && props.modelValue.length > 0) {
      selectedValue.value = props.options.filter(opt => {
        const optId = typeof opt === 'object' ? opt[props.trackBy] : opt;
        return props.modelValue.includes(optId);
      });
    } else {
      selectedValue.value = [];
    }
  } else {
    if (props.modelValue !== null && props.modelValue !== '') {
      const found = props.options.find(opt => {
        const optId = typeof opt === 'object' ? opt[props.trackBy] : opt;
        return optId === props.modelValue;
      });
      selectedValue.value = found || null;
    } else {
      selectedValue.value = null;
    }
  }
});
</script>

<style>
.multiselect-custom {
  min-height: 42px;
}

.multiselect-custom .multiselect__tags {
  min-height: 42px;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.5rem 2.5rem 0.5rem 0.75rem;
}

.multiselect-custom .multiselect__tags:focus-within {
  border-color: #0ea5e9;
  ring: 1px;
  ring-color: #0ea5e9;
}

.multiselect-error .multiselect__tags {
  border-color: #ef4444;
}

.multiselect-custom .multiselect__placeholder {
  color: #9ca3af;
  padding-top: 0;
  margin-bottom: 0;
}

.multiselect-custom .multiselect__single {
  margin-bottom: 0;
  padding-top: 0;
}

.multiselect-custom .multiselect__input,
.multiselect-custom .multiselect__single {
  font-size: 0.875rem;
  line-height: 1.5rem;
}

.multiselect-custom .multiselect__select {
  height: 40px;
  right: 1px;
  top: 1px;
}

.multiselect-custom .multiselect__select:before {
  border-color: #6b7280 transparent transparent;
  border-width: 5px 5px 0;
  top: 50%;
  margin-top: -2.5px;
}
</style>

