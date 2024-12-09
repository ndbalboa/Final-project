<template>
    <div>
      <h1>Departments</h1>
      <select v-model="selectedDepartment" @change="fetchDocuments">
        <option value="" disabled>Select a Department</option>
        <option v-for="department in departments" :key="department.id" :value="department.id">
          {{ department.department }}
        </option>
      </select>
  
      <h2 v-if="documents.length">Documents for {{ departmentName }}</h2>
      <table v-if="documents.length" border="1">
        <thead>
          <tr>
            <th>Document No</th>
            <th>Series No</th>
            <th>Date Issued</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Employees</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="document in documents" :key="document.id">
            <td>{{ document.document_no }}</td>
            <td>{{ document.series_no }}</td>
            <td>{{ document.date_issued }}</td>
            <td>{{ document.subject }}</td>
            <td>{{ document.description }}</td>
            <td>
              <ul>
                <li v-for="name in document.employee_names" :key="name">{{ name }}</li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
  
      <p v-else>No documents found for this department.</p>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        departments: [],
        selectedDepartment: "",
        documents: [],
        departmentName: "",
      };
    },
    methods: {
      fetchDepartments() {
        axios
          .get("/api/admin/thisdepartments")
          .then((response) => {
            this.departments = response.data;
          })
          .catch((error) => {
            console.error("Error fetching departments:", error);
          });
      },
      fetchDocuments() {
        if (this.selectedDepartment) {
          axios
            .get(`/api/admin/departments/${this.selectedDepartment}/thisdocuments`)
            .then((response) => {
              this.documents = response.data.documents;
              this.departmentName = response.data.department;
            })
            .catch((error) => {
              console.error("Error fetching documents:", error);
            });
        }
      },
    },
    mounted() {
      this.fetchDepartments();
    },
  };
  </script>
  