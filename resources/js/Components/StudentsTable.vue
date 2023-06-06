<template>
  <div>
    <div class="overflow-scroll">
      <table class="table-list striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Level</th>
            <th>Class</th>
            <th>Parents Contact</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students.data" :key="student.id">
            <td>{{student.name}}</td>
            <td>{{student.level}}</td>
            <td>{{student.class}}</td>
            <td>{{student.parent_contact}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <HPagination :data="paginationData" @change-page="(p) => getStudents(p)"></HPagination>
  </div>
</template>

<script setup>
  import { ref, onMounted, watch } from 'vue';
  import HPagination from '@/Components/HComponents/HPagination.vue';
  const paginationData = ref({
    from: 1,
    to: 1,
    total: 1,
    current: 1,
    prev: 1,
    next: 1,
    pages:[],
    last: 1
  })
  const maxVisible = ref(5)
  const startLimit = ref(1)
  const endLimit = ref(maxVisible.value)
  const disabled = ref(false)

  onMounted(() => {
    setTimeout(() => {
      getPagination(props.students)
    }, 500);
  })

watch(() => props.students, () => {
  getPagination(props.students)
});

  const props = defineProps({
    students: Object
  })

  const emit = defineEmits(['getStudents'])

  const getPagination = (data) => {
    setTimeout(() => {
      paginationData.value.from = data.from
      paginationData.value.to = data.to
      paginationData.value.total = data.total
      paginationData.value.current = data.current_page
      paginationData.value.prev = getPageNumber(data.prev_page_url)
      paginationData.value.next = getPageNumber(data.next_page_url)
      paginationData.value.last = data.last_page
      paginationData.value.pages = []
      if (data.current_page >= endLimit.value) {
        startLimit.value = endLimit.value
        endLimit.value = endLimit.value + maxVisible.value
      }else if (data.current_page <= startLimit.value && startLimit.value >= maxVisible.value) {
        endLimit.value = startLimit.value
        startLimit.value = startLimit.value - (maxVisible.value - 1)
      }
      const links = data.links.slice(1, -1);
      links.forEach((item, index) => {
        if (index >= (startLimit.value - 1) && index <= (endLimit.value - 1)) {
          paginationData.value.pages.push(item)
        }
      })
    }, 100);
  }

  const getPageNumber = (urlStr) => {
    if (urlStr) {
      const url = new URL(urlStr);
      const page = url.searchParams.get('page')
      return page
    }
    return false
  }

   const getStudents = (p) => {
    emit('getStudents', p)
    setTimeout(() => {
      getPagination(props.students)
    }, 300);
  }
</script>