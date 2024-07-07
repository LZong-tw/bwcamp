<template>
    <div class="mt-2">
      <div v-if="queryStr">查詢條件：{{ queryStr }}</div>
      <div class="text-danger mt-3">
        已取消報名： {{ cancelledCount }} 人
      </div>
      <table class="table table-bordered table-hover" id="applicantTable">
        <thead>
          <tr class="bg-success text-white">
            <th v-if="isSetting || isSettingCarer"></th>
            <th v-for="(item, key) in columns" :key="key" class="text-center" :data-field="key" :data-sortable="item.sort">
              {{ item.name }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="applicant in filteredApplicants" :key="applicant.id" :style="applicant.deleted_at ? 'color: rgba(120, 120, 120, 0.4)!important' : ''">
            <td v-if="isSetting || isSettingCarer" class="text-center">
              <input type="checkbox" :value="applicant.id" :id="'A' + applicant.id" @change="applicantTriggered($event, applicant.id)">
            </td>
            <td v-for="(item, key) in columns" :key="key">
              <template v-if="key === 'avatar' && applicant.avatar">
                <img :src="'/backend/' + applicant.camp.id + '/avatar/' + applicant.id" width="80" :alt="applicant.name">
              </template>
              <template v-else-if="key === 'name'">
                <a :href="getAttendeeInfoUrl(applicant)" target="_blank">{{ applicant.name }}</a>&nbsp;(報名序號：{{ applicant.id }})
                <div v-if="applicant.user" class="text-success">連結之帳號：{{ applicant.user.name }}({{ applicant.user.email }})</div>
              </template>
              <!-- Add more conditions for other column types -->
              <template v-else>
                {{ applicant[key] || "-" }}
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  
  export default {
    props: {
      initialApplicants: Array,
      columns: Object,
      isSetting: Boolean,
      isSettingCarer: Boolean,
      isShowVolunteers: Boolean,
      queryStr: String,
      campFullData: Object,
    },
    setup(props) {
      const applicants = ref(props.initialApplicants);
      const selectedApplicantIds = ref([]);
  
      const cancelledCount = computed(() => {
        return applicants.value.filter(a => a.deleted_at).length;
      });
  
      const filteredApplicants = computed(() => {
        // Implement filtering logic here if needed
        return applicants.value;
      });
  
      function applicantTriggered(event, id) {
        if (event.target.checked) {
          selectedApplicantIds.value.push(id);
        } else {
          selectedApplicantIds.value = selectedApplicantIds.value.filter(val => val !== id);
        }
      }
  
      function getAttendeeInfoUrl(applicant) {
        // Implement the logic to generate the URL
        return `/attendee-info/${applicant.id}`;
      }
  
      return {
        applicants,
        cancelledCount,
        filteredApplicants,
        applicantTriggered,
        getAttendeeInfoUrl,
      };
    }
  }
  </script>
  
  <style scoped>
  .wrapper1 {
    width: 400px;
    overflow-x: scroll;
    overflow-y: hidden;
    height: 20px;
  }
  .div1 {
    width: 600px;
    height: 20px;
  }
  </style>
  