<template>
    <h1>HRMS</h1>
    <input type="file" @change="handleFileUpload" accept=".csv" />
    <button @click="uploadCsv" class="group relative inline-flex h-12 items-center justify-center overflow-hidden rounded-md bg-neutral-950 px-6 font-medium text-neutral-200 transition hover:scale-110"><span>Upload</span><div class="absolute inset-0 flex h-full w-full justify-center [transform:skew(-12deg)_translateX(-100%)] group-hover:duration-1000 group-hover:[transform:skew(-12deg)_translateX(100%)]"><div class="relative h-full w-8 bg-white/20"></div></div></button>

    <!-- ローディングGIFを表示 -->
    <div v-if="loading">
        <img src="../resources/loading.gif" alt="loading ..." />
    </div>

    <div v-if="failedRecords.length">
      <h3>バリデーションに失敗したデータ</h3>
      <table border="1">
        <thead>
          <tr>
            <th>従業員ID</th>
            <th>名前</th>
            <th>部署</th>
            <th>性別</th>
            <th>入社日</th>
            <th>エラー内容</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(record, index) in failedRecords" :key="index">
            <td>{{ record.data.staff_id }}</td>
            <td>{{ record.data.staff_name }}</td>
            <td>{{ record.data.department }}</td>
            <td>{{ record.data.gender }}</td>
            <td>{{ record.data.start_date }}</td>
            <td>{{ record.errors.join(", ") }}</td>
          </tr>
        </tbody>
      </table>
    </div>

</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            csvFile: null,
            failedRecords: [],
            loading: false,
        };
    },
    methods: {
        handleFileUpload(event) {
            this.csvFile = event.target.files[0];
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
                this.failedRecords = [];
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.failedRecords = error.response.data.errors;
                } else {
                    alert("アップロードに失敗しました");
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>