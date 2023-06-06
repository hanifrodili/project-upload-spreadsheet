<template lang="pug">
.index
  div.p-10.mt-10.w-full
    h1.mb-2.font-black.text-lg Upload from file
    p.capitalize.mb-5.text-gray-500.underline Download excel template
    div.flex.flex-col
      input.mb-5(class="relative m-0 w-full min-w-0 flex flex-row-reverse rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary" id="file_input" type="file" ref="file")
      div.flex.flex-row.gap-3
        button.py-2.px-4.border.rounded-lg.border-purple-600.text-purple-600.flex-grow.uppercase(class="min-h-[32px] disabled:text-gray-300 disabled:border-gray-300" @click="uploadFile()" :disabled="loading") Upload
        button.py-2.px-4.border.rounded-lg.border-red-600.text-red-600.flex-grow.uppercase(class="min-h-[32px] disabled:text-gray-300 disabled:border-gray-300" @click="file.value = null" :disabled="loading") Cancel
    StudentsTable(class="pt-10" :students="students" @getStudents="getStudents")
</template>

<script setup>
import { ref, onMounted } from 'vue';
import XLSX from "xlsx";
import StudentsTable from '@/Components/StudentsTable.vue';

const file = ref(null)
const students = ref([])
const loading = ref(false)

onMounted(() => {
  getStudents(1)
})

const uploadFile = async () => {
  loading.value = true
  if (!file.value.files[0]) {
    loading.value = false
    alert("No file chosen.")
    return
  }
  const f = file.value.files[0];
  var reader = new FileReader();
  reader.onload = function () {
    var fileData = reader.result;
    var wb = XLSX.read(fileData, { type: 'binary' });

    wb.SheetNames.forEach(function (sheetName) {
      var rowObj = XLSX.utils.sheet_to_row_object_array(wb.Sheets[sheetName]);
      var jsonObj = JSON.stringify(rowObj);
    
      JSON.parse(jsonObj).forEach(obj => {
        let newObj = {
          name: obj["Name"],
          level: obj["Level"],
          class: obj["Class"],
          parent_contact: sanitizePhoneNumber(obj["Parents Contact"])
        }
        addStudent(newObj)
      });
    })
  };
  reader.readAsBinaryString(f);

  loading.value = false
}

const addStudent = (value) => {
  axios.post('/api/students', value)
    .then((response) => {
      if (response.status === 200) {
        if (response.data.msg) {
          console.log(response.data.msg);
        }else{
          getStudents(1)
        }
        
      }
    })
    .catch(error => {
      if (error) {
        console.log(error)
      }
    })
  file.value = null
}

const getStudents = async (p) => {
  if (p) {
    try {
      const resp = await axios.get('/api/students?page=' + p);
      students.value = resp.data
      // getPagination(resp.data)

    } catch (err) {
      // Handle Error Here
      console.error(err);
    }
  }
}

const sanitizePhoneNumber = (phoneNumber) => {
  const sanitizedNumber = phoneNumber.replace(/\ /g, '');
  // Check if the number starts with "+60"
  if (sanitizedNumber.startsWith('+60')) {
    return sanitizedNumber;
  } else if (sanitizedNumber.startsWith('0')) {
    // Remove the leading '0' and add "+60" to the beginning of the number
    return '+60' + sanitizedNumber.slice(1);
  } else if (sanitizedNumber.startsWith('60')) {
    // Remove the leading '0' and add "+60" to the beginning of the number
    return '+' + sanitizedNumber;
  } else {
    // Add "+60" to the beginning of the number
    return '+60' + sanitizedNumber;
  }
}
</script>

<style lang="scss" scoped></style>
