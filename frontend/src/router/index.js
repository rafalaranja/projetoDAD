import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import Dashboard from "../components/Dashboard.vue";
import Login from "../components/auth/Login.vue";
import ChangePassword from "../components/auth/ChangePassword.vue";
import ChangePin from "../components/auth/ChangePin.vue";
import Vcards from "../components/vcards/Vcard.vue";
import Transactions from "../components/transactions/Transactions.vue";
import User from "../components/users/User.vue";
import Users from "../components/users/Users.vue";
import TransactionTasks from "../components/transactions/TransactionTasks.vue";
import Task from "../components/vcards/Vcard.vue";
import vcardNew from "../components/vcards/vcardNew.vue";
import DeleteVcard from "../components/vcards/DeleteVcard.vue";
import Transaction from "../components/transactions/Transaction.vue";
import SendMoney from "../components/transactions/SendMoney.vue";
import Statistics from "../components/Statistics.vue";
import AskMoney from "../components/transactions/AskMoney.vue";
import VcardDetail from "../components/vcards/VcardDetail.vue";
import Categories from "../components/categories/Categories.vue";

import { useUserStore } from "../stores/users";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "Dashboard",
      component: Dashboard,
    },
    {
      path: "/about",
      name: "about",
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import("../views/AboutView.vue"),
    },
    {
      path: "/login",
      name: "Login",
      component: Login,
    },
    {
      path: "/password",
      name: "ChangePassword",
      component: ChangePassword,
      meta: { requiresAuth: true },
    },
    {
      path: "/pin",
      name: "ChangePin",
      component: ChangePin,
      meta: { requiresAuth: true },
    },
    {
      path: "/vcard",
      name: "Vcard",
      component: Vcards,
      meta: { requiresAuth: true },
    },
    {
      path: "/deleteVcard/",
      name: "deleteVcard",
      component: DeleteVcard,
      meta: { requiresAuth: true },
    },
    {
      path: "/tasks/current",
      name: "CurrentTasks",
      component: Vcards,
      props: { onlyCurrentTasks: true, tasksTitle: "Current Tasks" },
      meta: { requiresAuth: true },
    },
    {
      path: "/vcard/new",
      name: "NewTask",
      component: vcardNew,
      meta: { requiresAuth: true },
      //props: { id: -1 },
    },
    {
      path: "/tasks/:id",
      name: "Task",
      component: Task,
      props: (route) => ({ id: parseInt(route.params.id) }),
      meta: { requiresAuth: true },
    },
    {
      path: "/statistics",
      name: "Statistics",
      component: Statistics,
      meta: { requiresAuth: true },
    },
    {
      path: "/transactions",
      name: "Transactions",
      component: Transactions,
      meta: { requiresAuth: true },
    },
    {
      path: "/transactions/sendMoney",
      name: "SendMoney",
      component: SendMoney,
    },
    {
      path: "/transactions/askMoney",
      name: "AskMoney",
      component: AskMoney,
      meta: { requiresAuth: true },
    },
    {
      path: "/projects/new",
      name: "NewTransaction",
      component: Transaction,
      props: { id: -1 },
      meta: { requiresAuth: true },
    },
    {
      path: "/transactions/:id",
      name: "Transaction",
      component: Transaction,
      props: (route) => ({ id: parseInt(route.params.id) }),
      meta: { requiresAuth: true },
    },
    {
      path: "/users",
      name: "Users",
      component: Users,
      meta: { requiresAuth: true },
    },
    {
      path: "/users/:id",
      name: "User",
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: (route) => ({ id: parseInt(route.params.id) }),
      meta: { requiresAuth: true },
    },
    {
      path: "/projects/:id/tasks",
      name: "TransactionTasks",
      component: TransactionTasks,
      props: (route) => ({ id: parseInt(route.params.id) }),
      meta: { requiresAuth: true },
    },
    {
      path: "/projects/:id/tasks/new",
      name: "NewTaskOfTransaction",
      component: Task,
      props: (route) => ({
        id: -1,
        fixedTransaction: parseInt(route.params.id),
      }),
      meta: { requiresAuth: true },
    },
    {
      path: "/users",
      name: "Users",
      component: Users,
      meta: { requiresAuth: true },
    },
    {
      path: "/vcards",
      name: "Vcards",
      component: Vcards,
      meta: { requiresAuth: true },
    },
    {
      path: "/vcard/:id",
      name: "VcardDetail",
      component: VcardDetail,
      meta: { requiresAuth: true },
    },
    {
      path: "/categories",
      name: "Categories",
      component: Categories,
      meta: { requiresAuth: true },
    },
    {
      path: "/users/:id",
      name: "User",
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: (route) => ({ id: parseInt(route.params.id) }),
      meta: { requiresAuth: true },
    },
  ],
});

let handlingFirstRoute = true;
router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  if (handlingFirstRoute) {
    handlingFirstRoute = false;
    await userStore.restoreToken();
  }
  return next();
});

export default router;
