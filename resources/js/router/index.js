import { createRouter, createWebHistory } from 'vue-router';

import login from '../components/auth/login.vue';
import AdminLayout from '../components/admin/AdminLayout.vue';
import SecretaryLayout from '../components/secretary/SecretaryLayout.vue'; 
import UserLayout from '../components/user/UserLayout.vue';
import AdminDashboard from '../components/admin/AdminDashboard.vue';
import SecretaryDashboard from '../components/secretary/SecretaryDashboard.vue'; 
import UserDashboard from '../components/user/UserDashboard.vue';
import UploadDocument from '../components/admin/upload-search/UploadDocument.vue';
import SearchDocument from '../components/admin/upload-search/SearchDocument.vue';
import UserSearchDocument from '../components/user/UserSearchDocument.vue';
import DocumentsTravelOrder from '../components/admin/documents/DocumentsTravelOrder.vue';
import DocumentsOfficeOrder from '../components/admin/documents/DocumentsOfficeOrder.vue';
import DocumentsSpecialOrder from '../components/admin/documents/DocumentsSpecialOrder.vue';
import AddNewEmployee from '../components/admin/employees/AddNewEmployee.vue';
import ListOfEmployee from '../components/admin/employees/ListOfEmployee.vue';
import Settings from '../components/Settings.vue';
import UserProfile from '../components/user/UserProfile.vue';
import CreateUserAccount from '../components/admin/employees/CreateUserAccount.vue';
import EmployeeInformation from '../components/admin/employees/EmployeeInformation.vue';
import EmployeeDocuments from '../components/admin/employees/EmployeeDocuments.vue';
import ChangeCredentials from '../components/user/ChangeCredentials.vue';
import EmployeeDetails from '../components/admin/employees/EmployeeDetails.vue';
import DeactivatedEmployees from '../components/admin/employees/DeactivatedEmployees.vue';
import ScanDocument from '../components/admin/upload-search/ScanDocument.vue';
import AutoFill from '../components/admin/upload-search/AutoFill.vue';
import DocumentDetails from '../components/admin/documents/DocumentDetails.vue';
import UserTravelOrder from '../components/user/UserTravelOrder.vue';
import UserOfficeOrder from '../components/user/UserOfficeOrder.vue';
import UserSpecialOrder from '../components/user/UserSpecialOrder.vue';
import UserDocumentDetails from '../components/user/UserDocumentDetails.vue';
import MailList from '../components/admin/Mails/MailList.vue';
import NewMail from '../components/admin/Mails/NewMail.vue';


import SecPageAutoFill from '../components/secretary/SecPageAutoFill.vue';
import SecPageAddNewEmployee from '../components/secretary/SecPageAddNewEmployee.vue';
import SecPageDeactivatedEmployees from '../components/secretary/SecPageDeactivatedEmployees.vue';
import SecPageDocumentDetails from '../components/secretary/SecPageDocumentDetails.vue';
import SecPageDocumentsOfficeOrder from '../components/secretary/SecPageDocumentsOfficeOrder.vue';
import SecPageScanDocument from '../components/secretary/SecPageScanDocument.vue';
import SecPageSearchDocument from '../components/secretary/SecPageSearchDocument.vue';
import SecPageDocumentsSpecialOrder from '../components/secretary/SecPageDocumentsSpecialOrder.vue';
import SecPageDocumentsTravelOrder from '../components/secretary/SecPageDocumentsTravelOrder.vue';
import SecPageEmployeeDetails from '../components/secretary/SecPageEmployeeDetails.vue';
import SecPageEmployeeDocuments from '../components/secretary/SecPageEmployeeDocuments.vue';
import SecPageEmployeeInformation from '../components/secretary/SecPageEmployeeInformation.vue';
import SecPageListOfEmployee from '../components/secretary/SecPageListOfEmployee.vue';
import SecPageUploadDocument from '../components/secretary/SecPageUploadDocument.vue';
import GeneratedReports from '../components/admin/reports/GeneratedReports.vue';
import GenerateOfficeReports from '../components/admin/reports/GenerateOfficeReports.vue';
import TravelOrderDocDetails from '../components/admin/documents/TravelOrderDocDetails.vue';
import GenerateTravelReports from '../components/admin/reports/GenerateTravelReports.vue';
import GenerateSpecialReports from '../components/admin/reports/GenerateSpecialReports.vue';

import DepartmentDashboard from '../components/department/DepartmentDashboard.vue';
import DepartmentLayout from '../components/department/DepartmentLayout.vue';
import CreateDepartmentAccount from '../components/admin/employees/CreateDepartmentAccount.vue';
import DepartmentTravel from '../components/department/DepartmentTravel.vue';
import AddDocumentType from '../components/admin/documents/AddDocumentType.vue';
import DepartmentUploadDocument from '../components/department/DepartmentUploadDocument.vue';
import OtherDocuments from '../components/user/OtherDocuments.vue';
import SecPageMailList from '../components/secretary/SecPageMailList.vue';
import SecPageNewMail from '../components/secretary/SecPageNewMail.vue';
import SecPageAddDocumentType from '../components/secretary/SecPageAddDocumentType.vue';
import DepartmentOtherDocuments from '../components/department/DepartmentOtherDocuments.vue';
import DepartmentSpecial from '../components/department/DepartmentSpecial.vue';
import DepartmentOffice from '../components/department/DepartmentOffice.vue';

function isAuthenticated() {
  return !!localStorage.getItem('token');
}

function getUserRole() {
  const user = JSON.parse(localStorage.getItem('user'));
  return user ? user.role : null;
}

const routes = [
  { path: '/', component: login },
  
  // Admin Dashboard routes
  {
    path: '/admin-dashboard',
    component: AdminLayout,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated() || getUserRole() !== 'admin') {
        return next('/');
      }
      next();
    },
    children: [
      { path: '', component: AdminDashboard },
      { path: 'upload-document', component: UploadDocument },
      { path: 'scan-document', component: ScanDocument },
      { path: 'autofill', name: 'Autofill', component: AutoFill, props: true },
      { path: 'search-document', component: SearchDocument },
      { path: 'documents/travel-order', component: DocumentsTravelOrder },
      { path: 'documents/office-order', component: DocumentsOfficeOrder },
      { path: 'documents/special-order', component: DocumentsSpecialOrder },
      { path: 'adddocumenttype', component: AddDocumentType},
      { path: 'documents/:id', name: 'DocumentDetails', component: DocumentDetails},
      { path: 'documents/:id', name: 'TravelOrderDocDetails', component: TravelOrderDocDetails},
      { path: 'employee/add', component: AddNewEmployee },
      { path: 'employee/list', name: 'EmployeeList', component: ListOfEmployee },
      { path: 'employee/deactivated', component: DeactivatedEmployees },
      { path: 'employee/:id', name: 'EmployeeInformation', component: EmployeeInformation },
      { path: 'createuser', component: CreateUserAccount },
      { path: 'createdepartment', component: CreateDepartmentAccount },
      { path: 'admin/employee/:id', name: 'EmployeeDetails', component: EmployeeDetails },
      { path: 'employees/:id/documents', name: 'EmployeeDocuments', component: EmployeeDocuments, props: true },
      { path: 'generatedReports', name: 'GeneratedReports', component: GeneratedReports},
      { path: 'officeOrderReports', name: 'GenerateOfficeReports', component: GenerateOfficeReports},
      { path: 'generateTravelReports', name: 'GenerateTravelReports', component: GenerateTravelReports},
      { path: 'generateSpecialReports', name: 'GenerateSpecialReports', component: GenerateSpecialReports},
      { path: 'settings', component: Settings },
      { path: 'mail/list', component: MailList },
      { path: 'mail/new', component: NewMail },



    ],
  },

  // Secretary Dashboard routes
  {
    path: '/secretary-dashboard',
    component: SecretaryLayout,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated() || getUserRole() !== 'secretary') {
        return next('/');
      }
      next();
    },
    children: [
      { path: '', component: SecretaryDashboard },
      { path: 'upload-document', component: SecPageUploadDocument },
      { path: 'scan-document', component: SecPageScanDocument },
      { path: 'autofill', name: 'SecPageAutofill', component: SecPageAutoFill, props: true },
      { path: 'search-document', component: SecPageSearchDocument },
      { path: 'documents/travel-order', component: SecPageDocumentsTravelOrder },
      { path: 'documents/office-order', component: SecPageDocumentsOfficeOrder },
      { path: 'documents/special-order', component: SecPageDocumentsSpecialOrder },
      { path: 'documents/:id', name: 'SecPageDocumentDetails', component: SecPageDocumentDetails},
      { path: 'employee/add', component: SecPageAddNewEmployee },
      { path: 'employee/list', name:'SecPageEmployeeList', component: SecPageListOfEmployee },
      { path: 'employee/deactivated', component: SecPageDeactivatedEmployees },
      { path: 'employee/:id', name: 'SecPageEmployeeInformation', component: SecPageEmployeeInformation },
      { path: 'secretary/employee/:id', name: 'SecPageEmployeeDetails', component: SecPageEmployeeDetails },
      { path: 'employees/:id/documents', name: 'SecPageEmployeeDocuments', component: SecPageEmployeeDocuments, props: true },
      { path: 'generatedReports', name: 'SecPageGeneratedReports', component: GeneratedReports},
      { path: 'officeOrderReports', name: 'SecPageGenerateOfficeReports', component: GenerateOfficeReports},
      { path: 'generateTravelReports', name: 'SecPageGenerateTravelReports', component: GenerateTravelReports},
      { path: 'generateSpecialReports', name: 'SecPageGenerateSpecialReports', component: GenerateSpecialReports},
      { path: 'mail/list', component: SecPageMailList },
      { path: 'mail/new', component: SecPageNewMail },
      { path: 'adddocumenttype', component: SecPageAddDocumentType},
      { path: 'settings', component: Settings },
      
    ],
  },
    // Secretary Dashboard routes
    {
      path: '/department-dashboard',
      component: DepartmentLayout,
      beforeEnter: (to, from, next) => {
        if (!isAuthenticated() || getUserRole() !== 'department') {
          return next('/');
        }
        next();
      },
      children: [
        { path: '', component: DepartmentDashboard },
        { path: 'department/documents/travel-order', component: DepartmentTravel },
        { path: 'department/documents/special-order', component: DepartmentSpecial },
        { path: 'department/documents/office-order', component: DepartmentOffice },
        { path: 'department/documents/others', component: DepartmentOtherDocuments },
        { path: 'upload-document', component: DepartmentUploadDocument },
        { path: 'documents/:id', name: 'DepartmentDocumentDetails', component: DocumentDetails},
        { path: 'mail/list', component: MailList },
        { path: 'mail/new', component: NewMail },
        { path: 'settings', component: Settings },
        
      ],
    },

  // User Dashboard routes
  {
    path: '/user-dashboard',
    component: UserLayout,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated() || getUserRole() !== 'user') {
        return next('/');
      }
      next();
    },
    children: [
      { path: '', component: UserDashboard },
      { path: 'search-document', component: UserSearchDocument },
      { path: 'documents/:id', name: 'UserDocumentDetails', component: UserDocumentDetails},
      { path: 'documents/travel-order', component: UserTravelOrder },
      { path: 'documents/office-order', component: UserOfficeOrder },
      { path: 'documents/special-order', component: UserSpecialOrder },
      { path: 'documents/others', component: OtherDocuments },
      { path: 'user-profile', component: UserProfile }, // User profile view and edit
      { path: 'settings/change-credentials', component: ChangeCredentials }, // Change credentials option
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global route guard to check authentication for routes that require it
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated()) {
      return next('/');
    }
  }
  next();
});

export default router;
