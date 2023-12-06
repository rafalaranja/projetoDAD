import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import Dashboard from "../components/Dashboard.vue";
import Login from "../components/auth/Login.vue";
import ChangePassword from "../components/auth/ChangePassword.vue";
import ChangePin from "../components/auth/ChangePin.vue";
import Tasks from "../components/vcards/Vcard.vue";
import Transactions from "../components/transactions/Transactions.vue";
import User from "../components/users/User.vue";
import Users from "../components/users/Users.vue";
import TransactionTasks from "../components/transactions/TransactionTasks.vue";
import Task from "../components/vcards/Vcard.vue";
import Transaction from "../components/transactions/Transaction.vue";
import SendMoney from "../components/transactions/SendMoney.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
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
      path: "/dashboard",
      name: "Dashboard",
      component: Dashboard,
    },
    {
      path: "/vcard",
      name: "Vcard",
      component: Tasks,
    },
    {
      path: "/tasks/current",
      name: "CurrentTasks",
      component: Tasks,
      props: { onlyCurrentTasks: true, tasksTitle: "Current Tasks" },
    },
    {
      path: "/tasks/new",
      name: "NewTask",
      component: Task,
      props: { id: -1 },
    },
    {
      path: "/tasks/:id",
      name: "Task",
      component: Task,
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
    {
      path: "/transactions",
      name: "Transactions",
      component: Transactions,
    },
    {
      path: "/transactions/send",
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
      path: "/users/:id",
      name: "User",
      component: User,
      //props: true
      // Replaced with the following line to ensure that id is a number
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
  ],
});

export default router;
