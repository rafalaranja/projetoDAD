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
    },
    {
      path: "/pin",
      name: "ChangePin",
      component: ChangePin,
    },
    {
      path: "/vcard",
      name: "Vcard",
      component: Vcards,
    },
    {
      path: "/deleteVcard/",
      name: "deleteVcard",
      component: DeleteVcard,
    },
    {
      path: "/tasks/current",
      name: "CurrentTasks",
      component: Vcards,
      props: { onlyCurrentTasks: true, tasksTitle: "Current Tasks" },
    },
    {
      path: "/vcard/new",
      name: "NewTask",
      component: vcardNew,
      //props: { id: -1 },
    },
    {
      path: "/tasks/:id",
      name: "Task",
      component: Task,
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
    {
      path: "/statistics",
      name: "Statistics",
      component: Statistics,
    },
    {
      path: "/transactions",
      name: "Transactions",
      component: Transactions,
    },
    {
      path: "/transactions/sendMoney",
      name: "SendMoney",
      component: SendMoney,
    },
    {
      path: "/projects/new",
      name: "NewTransaction",
      component: Transaction,
      props: { id: -1 },
    },
    {
      path: "/transactions/:id",
      name: "Transaction",
      component: Transaction,
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
    {
      path: "/users",
      name: "Users",
      component: Users,
    },
    {
      path: "/users/:id",
      name: "User",
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
    {
      path: "/projects/:id/tasks",
      name: "TransactionTasks",
      component: TransactionTasks,
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
    {
      path: "/projects/:id/tasks/new",
      name: "NewTaskOfTransaction",
      component: Task,
      props: (route) => ({
        id: -1,
        fixedTransaction: parseInt(route.params.id),
      }),
    },
    {
      path: "/users",
      name: "Users",
      component: Users,
    },
    {
      path: "/vcards",
      name: "Vcards",
      component: Vcards,
    },
    {
      path: "/vcard/:id",
      name: "VcardDetail",
      component: VcardDetail,
    },
    {
      path: "/Categories",
      name: "Categories",
      component: Categories,
    },
    {
      path: "/users/:id",
      name: "User",
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: (route) => ({ id: parseInt(route.params.id) }),
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
