<template>
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 p-6">
    <!-- ヘッダー -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">CSV アップロード</h1>

    <!-- ファイル選択エリア -->
    <div 
      class="w-80 flex flex-col items-center px-4 py-6 bg-white rounded-lg shadow-md border-2 border-dashed border-blue-500 cursor-pointer hover:bg-blue-50 transition"
      @dragover.prevent
      @dragenter.prevent
      @drop="handleDrop"
    >
        <input type="file" class="hidden" ref="fileInput" @change="handleFileUpload" />
        <label @click="triggerFileInput" class="flex flex-col items-center">
            <svg class="w-8 h-8 mb-3 fill-current text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M16.88 8.88l-5.66-5.66A2 2 0 009.17 3H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4.17a2 2 0 00-.59-1.42zM14 15H6a1 1 0 01-1-1V6a1 1 0 011-1h3v4a1 1 0 001 1h4v4a1 1 0 01-1 1zm-3-5V5.41L14.59 10H11z" />
            </svg>
            <span v-if="!selectFlag" class="mt-2 text-base leading-normal text-center">ファイルを選択<br>または<br>ドラッグ&ドロップ</span>
            <span v-else class="mt-2 text-base leading-normal text-gray-900">{{ selectedFileName }}</span>
        </label>
    </div>

    <!-- アップロードボタン -->
    <button @click="uploadCsv" class="mt-4 px-6 py-3 bg-blue-500 text-white font-bold rounded-lg shadow-md hover:bg-blue-600 transition">
        アップロード
    </button>

    <!-- ローディングオーバーレイ -->
    <div v-if="loading" class="fixed inset-0 flex justify-center items-center z-50 backdrop-blur-md"
    style="background: rgba(0, 0, 0, 0.5);">
        <div class="flex flex-col items-center">
            <img src="../resources/loading.gif" alt="Loading..." class="w-20 h-20" />
            <p class="text-white text-lg mt-2">読み込み中...</p>
        </div>
    </div>

    <div v-if="failedRecords.length" class="mt-4">
        <h3 class="text-lg font-bold mb-2">不正なデータのため書き込みできませんでした</h3>
        
        <div class="overflow-x-auto">
            <table class="table-auto border-collapse border border-gray-300 w-full">
            <!-- ヘッダー -->
            <thead class="bg-gray-100">
                <tr>
                <th class="border border-gray-400 px-4 py-2">従業員ID</th>
                <th class="border border-gray-400 px-4 py-2">名前</th>
                <th class="border border-gray-400 px-4 py-2">部署</th>
                <th class="border border-gray-400 px-4 py-2">性別</th>
                <th class="border border-gray-400 px-4 py-2">入社日</th>
                <th class="border border-gray-400 px-4 py-2">エラー内容</th>
                </tr>
            </thead>
            <!-- ボディ -->
            <tbody>
                <tr v-for="(record, index) in failedRecords" :key="index" class="hover:bg-gray-50">
                <td class="border border-gray-400 px-4 py-2">{{ record.data.staff_id }}</td>
                <td class="border border-gray-400 px-4 py-2">{{ record.data.staff_name }}</td>
                <td class="border border-gray-400 px-4 py-2">{{ record.data.department }}</td>
                <td class="border border-gray-400 px-4 py-2">{{ record.data.gender }}</td>
                <td class="border border-gray-400 px-4 py-2">{{ record.data.start_date }}</td>
                <td class="border border-gray-400 px-4 py-2">{{ record.errors.join(', ') }}</td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>

</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            csvFile: null,
            selectFlag: false,
            selectedFileName: "",
            failedRecords: [],
            loading: false,
        };
    },

    methods: {
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.csvFile = file;
                this.selectFlag = true;
                this.selectedFileName = file.name;
            }
        },
        handleDrop(event) {
            event.preventDefault();
            const file = event.dataTransfer.files[0];
            if (file) {
                this.csvFile = file;
                this.selectFlag = true;
                this.selectedFileName = file.name;
            }
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        async uploadCsv() {
            if (!this.csvFile) {
                alert("CSVファイルを選択してください");
                return;
            }

            let formData = new FormData();
            formData.append("csv_file", this.csvFile);

            this.loading = true;

            try {
                const response = await axios.post("http://localhost/api/up-load", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                alert("アップロード成功!");
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.failedRecords = error.response.data.errors;
                } else {
                    alert("アップロードに失敗しました");
                }
            } finally {
                this.loading = false;
                this.csvFile = null;
                this.selectFlag = false;
                this.selectedFileName = "";
            }
        },
    },
};
</script>