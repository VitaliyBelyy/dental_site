const Menu = [
    {
        title: "Главная",
        icon: "mdi-home",
        name: "home"
    },
    {
        title: "Календарь",
        icon: "mdi-calendar-month",
        name: "dashboard.calendar"
    },
    {
        title: "Пациенты",
        icon: "mdi-account-group",
        name: "dashboard.patients"
    },
    {
        title: "Услуги",
        icon: "mdi-view-list",
        name: "dashboard.services",
        admin: true
    },
    {
        title: "Пользователи",
        icon: "mdi-account-cog",
        name: "dashboard.users",
        admin: true
    },
    {
        title: "Статистика",
        icon: "mdi-chart-areaspline",
        name: "dashboard.statistics",
        admin: true
    },
];

export default Menu;
