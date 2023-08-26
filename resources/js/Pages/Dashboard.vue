<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>
        <div
            id="crypto-modal"
            tabindex="-1"
            aria-hidden="true"
            :class="{ 'hidden': !isTableOpen}"
            class="flex  z-50 p-4 top-0 left-0 right-0 fixed items-center justify-center overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)]"
        >
            <div class="m-auto w-full max-w-4xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button
                        type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="crypto-modal"
                        @click="isTableOpen = false"
                    >
                        <svg
                            class="w-3 h-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                            />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <!-- Modal header -->
                    <div class="bg-gray-50 px-6 py-4 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                            Table builder
                        </h3>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Please choose the number of rows and columns
                        </p>
                        <div class="flex my-2 gap-8">
                            <div>
                                <InputLabel
                                    for="columns"
                                    value="Columns"
                                />
                                <TextInput
                                    id="columns"
                                    v-model="newColumns"
                                    type="number"
                                    class="mt-1 block w-full p-2"
                                    required
                                />
                            </div>
                            <div>
                                <InputLabel
                                    for="rows"
                                    value="Rows"
                                />
                                <TextInput
                                    id="rows"
                                    v-model="newRows"
                                    type="number"
                                    class="mt-1 block w-full p-2"
                                />
                            </div>
                            <div>
                                <PrimaryButton
                                    class="mt-6"
                                    @click.prevent="buildTable"
                                >
                                    <font-awesome-icon
                                        :icon="isTableGenerating ? 'circle-notch' : 'table'"
                                        class="p-0.5"
                                        :spin="isTableGenerating"
                                    />
                                </PrimaryButton>
                            </div>
                        </div>
                        <p class="text-sm pb-2 font-normal text-gray-500 dark:text-gray-400">
                            Rows can be added later dynamically
                        </p>
                        <div
                            v-if="showGeneratedTable"
                            class="py-2"
                        >
                            <div class="relative overflow-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th
                                                v-for="(column, index) in columns"
                                                :key="index"
                                                scope="col"
                                                class="px-6 py-3"
                                            >
                                                <TextInput
                                                    id="columns"
                                                    v-model="form.columnNames[index]"
                                                    class="mt-1 block w-full p-2"
                                                    required
                                                />
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(row, rowIndex) in rows"
                                            :key="rowIndex"
                                            class="bg-white border-b dark:bg-gray-900 dark:border-gray-700"
                                        >
                                            <td
                                                v-for="(column, columnIndex) in columns"
                                                :key="columnIndex"
                                                class="px-6 py-4 whitespace-nowrap"
                                            >
                                                <TextInput
                                                    id="columns"
                                                    v-model="form.rowNames[columnIndex][rowIndex]"
                                                    class="mt-1 block w-full p-2"
                                                    required
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="my-2">
                                <PrimaryButton
                                    class="mt-6"
                                    @click.prevent="submitTableData"
                                >
                                    Create Table
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white align-middle dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <PrimaryButton
                            class="float-right mb-6"
                            @click="openTableBuilder"
                        >
                            Create Table
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const isTableOpen = ref(false);
const newColumns = ref();
const newRows = ref();
const columns = ref([]);
const rows = ref([]);
const isTableGenerating = ref(false);
const showGeneratedTable = ref(false);

const form = useForm({
    columns: [],
    rows: [],
    columnNames: [],
    rowNames: [],
});
function openTableBuilder() {
    isTableOpen.value = true;
}

function buildTable() {
    isTableGenerating.value = true;
    columns.value = [];
    rows.value = [];
    form.columns = [];
    form.rows = [];
    for (let i = 1; i <= newColumns.value; i++) {
        columns.value.push(`Column ${i + 1}`);
        form.columnNames.push('');
    }
    for (let i = 1; i <= newRows.value; i++) {
        rows.value.push(`${i + 1}`);
        form.rowNames.push([]);
    }
    showGeneratedTable.value = true;
    setTimeout(() => {
        isTableGenerating.value = false;
    }, 500);
}

function submitTableData() {
    console.log(form);
}
</script>
