<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Breadcrumb Navigation -->
      <div class="mb-6">
        <router-link
          :to="`/businesses/${cashbook?.business_id || businessId}`"
          class="text-sky-600 hover:text-sky-800 text-sm font-medium mb-4 inline-flex items-center"
        >
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Business
        </router-link>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="bg-white rounded-lg shadow p-12 text-center">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div>
        <p class="text-gray-500 mt-4">Loading cashbook details...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        {{ error }}
      </div>

      <!-- Cashbook Content -->
      <div v-else-if="cashbook" class="space-y-6">
        <!-- Cash Details Section -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Cash Details</h2>
          </div>
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <h1 class="text-2xl font-bold text-gray-900">{{ cashbook.title }}</h1>
              <span
                :class="{
                  'bg-emerald-100 text-emerald-800': cashbook.status === 'active',
                  'bg-red-100 text-red-800': cashbook.status === 'inactive',
                  'bg-yellow-100 text-yellow-800': cashbook.status === 'pending',
                  'bg-gray-100 text-gray-800': cashbook.status === 'suspended'
                }"
                class="px-3 py-1 text-sm font-medium rounded-full"
              >
                {{ cashbook.status }}
              </span>
            </div>
            <div v-if="cashbook.description" class="mt-2">
              <p class="text-gray-600">{{ cashbook.description }}</p>
            </div>
          </div>
        </div>

        <!-- Cash In Section -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Cash In</h2>
            <button
              @click="openAddForm('in')"
              class="btn-primary text-sm"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span>Add Transaction</span>
            </button>
          </div>
          <div class="p-6">
            <!-- Cash In Transactions Table -->
            <div v-if="cashInTransactions.length > 0" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Party</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="transaction in cashInTransactions" :key="transaction.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDate(transaction.transaction_datetime) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ transaction.party_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                      +{{ formatCurrency(transaction.amount) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ transaction.description || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <button
                        @click="editTransaction(transaction, 'in')"
                        class="text-sky-600 hover:text-sky-800 mr-3"
                      >
                        Edit
                      </button>
                      <button
                        @click="confirmDeleteTransaction(transaction)"
                        class="text-red-600 hover:text-red-800"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-center text-gray-500 py-8">
              No cash in transactions yet.
            </div>
          </div>
        </div>

        <!-- Cash Out Section -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Cash Out</h2>
            <button
              @click="openAddForm('out')"
              class="btn-primary text-sm"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              <span>Add Transaction</span>
            </button>
          </div>
          <div class="p-6">
            <!-- Cash Out Transactions Table -->
            <div v-if="cashOutTransactions.length > 0" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Party</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="transaction in cashOutTransactions" :key="transaction.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDate(transaction.transaction_datetime) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ transaction.party_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                      -{{ formatCurrency(transaction.amount) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ transaction.description || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <button
                        @click="editTransaction(transaction, 'out')"
                        class="text-sky-600 hover:text-sky-800 mr-3"
                      >
                        Edit
                      </button>
                      <button
                        @click="confirmDeleteTransaction(transaction)"
                        class="text-red-600 hover:text-red-800"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-center text-gray-500 py-8">
              No cash out transactions yet.
            </div>
          </div>
        </div>
      </div>

      <!-- Transaction Form Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
        @click.self="closeModal"
      >
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
          <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center z-10">
            <h3 class="text-xl font-bold text-gray-900">
              {{ editingTransaction ? 'Edit Transaction' : (currentFormType === 'in' ? 'Add Cash In Transaction' : 'Add Cash Out Transaction') }}
            </h3>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 transition-colors p-1"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <form @submit.prevent="handleSubmit(currentFormType)" class="p-6">
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Amount *</label>
                  <input
                    v-model="form.amount"
                    type="number"
                    step="0.01"
                    min="0"
                    @blur="validateField('amount')"
                    :class="['input-field', { 'input-error': errors.amount && touched.amount }]"
                  />
                  <span v-if="errors.amount && touched.amount" class="error-message">{{ errors.amount }}</span>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Party Name *</label>
                  <input
                    v-model="form.party_name"
                    type="text"
                    @blur="validateField('party_name')"
                    :class="['input-field', { 'input-error': errors.party_name && touched.party_name }]"
                  />
                  <span v-if="errors.party_name && touched.party_name" class="error-message">{{ errors.party_name }}</span>
                </div>

                <div>
                  <DateTimePicker
                    v-model="form.transaction_datetime"
                    label="Transaction Date"
                    :required="true"
                    :error="errors.transaction_datetime && touched.transaction_datetime ? errors.transaction_datetime : ''"
                    @blur="validateField('transaction_datetime')"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea
                    v-model="form.description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                  ></textarea>
                </div>

                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700">Remark</label>
                  <textarea
                    v-model="form.remark"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                  ></textarea>
                </div>
              </div>

              <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                {{ error }}
              </div>

              <div class="flex justify-end space-x-2 pt-4">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="submitting"
                  class="px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 disabled:opacity-50"
                >
                  {{ submitting ? 'Saving...' : 'Save' }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTransactionStore } from '../stores/transaction';
import { useCashbookStore } from '../stores/cashbook';
import { useValidation } from '../composables/useValidation';
import DateTimePicker from '../components/DateTimePicker.vue';

const route = useRoute();
const router = useRouter();

const transactionStore = useTransactionStore();
const cashbookStore = useCashbookStore();
const { errors, touched, validateField, validateForm, clearErrors, setErrors } = useValidation();
const loading = ref(true);
const submitting = ref(false);
const error = ref(null);
const editingTransaction = ref(null);
const transactions = ref([]);
const cashbook = ref(null);
const businessId = ref(null);
const showModal = ref(false);
const currentFormType = ref('in'); // Track which form is open

const form = reactive({
  cashbook_id: null,
  type: 'in',
  amount: '',
  party_name: '',
  transaction_datetime: new Date().toISOString().slice(0, 16),
  description: '',
  remark: '',
  status: 'active',
});

const validationRules = {
  cashbook_id: ['required'],
  type: ['required'],
  amount: ['required', 'numeric'],
  party_name: ['required', 'min:2', 'max:255'],
  transaction_datetime: ['required'],
};

const cashInTransactions = computed(() => {
  return transactions.value.filter(t => t.type === 'in');
});

const cashOutTransactions = computed(() => {
  return transactions.value.filter(t => t.type === 'out');
});

onMounted(async () => {
  await loadCashbook();
  await loadTransactions();
});

const loadCashbook = async () => {
  try {
    loading.value = true;
    const cashbookData = await cashbookStore.fetchCashbook(route.params.id);
    cashbook.value = cashbookData;
    businessId.value = cashbookData.business_id;
    form.cashbook_id = cashbookData.id;
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load cashbook';
    console.error('Failed to load cashbook', err);
  } finally {
    loading.value = false;
  }
};

const loadTransactions = async () => {
  try {
    const response = await transactionStore.fetchTransactions({
      cashbook_id: route.params.id,
    });
    transactions.value = response.data || [];
  } catch (err) {
    console.error('Failed to load transactions', err);
  }
};

const openAddForm = (type) => {
  currentFormType.value = type;
  form.type = type;
  resetForm();
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};

const editTransaction = (transaction, type) => {
  editingTransaction.value = transaction;
  currentFormType.value = type;
  form.type = transaction.type;
  Object.assign(form, {
    cashbook_id: cashbook.value.id,
    type: transaction.type,
    amount: transaction.amount,
    party_name: transaction.party_name,
    transaction_datetime: new Date(transaction.transaction_datetime).toISOString().slice(0, 16),
    description: transaction.description || '',
    remark: transaction.remark || '',
    status: transaction.status || 'active',
  });
  showModal.value = true;
};

const confirmDeleteTransaction = async (transaction) => {
  if (confirm(`Are you sure you want to delete this transaction?`)) {
    try {
      await transactionStore.deleteTransaction(transaction.id);
      await loadTransactions();
    } catch (err) {
      alert('Failed to delete transaction');
    }
  }
};

const resetForm = () => {
  editingTransaction.value = null;
  clearErrors();
  error.value = null;
  Object.assign(form, {
    cashbook_id: cashbook.value.id,
    type: currentFormType.value,
    amount: '',
    party_name: '',
    transaction_datetime: new Date().toISOString().slice(0, 16),
    description: '',
    remark: '',
    status: 'active',
  });
};

const handleSubmit = async (type) => {
  clearErrors();
  error.value = null;

  // Ensure type is set correctly
  form.type = type;

  if (!validateForm(form, validationRules)) {
    return;
  }

  submitting.value = true;

  try {
    const formData = { ...form };
    Object.keys(formData).forEach(key => {
      if (formData[key] === '') {
        formData[key] = null;
      }
    });

    if (editingTransaction.value) {
      await transactionStore.updateTransaction(editingTransaction.value.id, formData);
    } else {
      await transactionStore.createTransaction(formData);
    }
    resetForm();
    closeModal();
    await loadTransactions();
  } catch (err) {
    if (err.response?.data?.errors) {
      setErrors(err.response.data.errors);
    } else {
      error.value = err.message || 'Failed to save transaction';
    }
  } finally {
    submitting.value = false;
  }
};


const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString();
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(amount);
};
</script>
