<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 bg-white p-6 rounded-lg shadow-lg">
      <h1 class="text-3xl font-semibold text-center text-gray-800">Upload CSV of Names</h1>

      <div class="mt-6">
        <input
          type="file"
          @change="handleFileUpload"
          class="w-full text-sm text-gray-500 file:py-2 file:px-4 file:border file:border-gray-300 file:bg-gray-100 file:rounded-md hover:file:bg-gray-200"
        />
      </div>

      <div v-if="parsedNames.length" class="mt-8">
        <h2 class="text-2xl font-medium text-gray-700">Parsed Names</h2>
        <ul class="space-y-4 mt-4">
          <li
            v-for="(person, index) in parsedNames"
            :key="index"
            class="p-4 bg-gray-50 rounded-md shadow-sm hover:bg-gray-100"
          >
            <strong class="text-lg text-gray-900">{{ person.title }} {{ person.first_name }} {{ person.initial }} {{ person.last_name }}</strong>
          </li>
        </ul>
      </div>

      <div v-if="errorMessage" class="mt-6 text-red-500 text-center">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      file: null,
      parsedNames: [],
      errorMessage: ""
    };
  },
  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.parseFile(file);
      }
    },
    async parseFile(file) {
      const formData = new FormData();
      formData.append('file', file);

      try {
        const response = await axios.post('/api/parse-names', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        if (response.data.success) {
          this.parsedNames = response.data.names.flat();
        } else {
          this.errorMessage = 'Failed to parse the file.';
        }
      } catch (error) {
        this.errorMessage = 'An error occurred while processing the file.';
      }
    }
  }
};
</script>

<style scoped>

</style>
