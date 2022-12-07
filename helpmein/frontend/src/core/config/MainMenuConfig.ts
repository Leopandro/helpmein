export interface MenuItem {
  heading?: string;
  sectionTitle?: string;
  route?: string;
  pages?: Array<MenuItem>;
  svgIcon?: string;
  fontIcon?: string;
  sub?: Array<MenuItem>;
}

const MainMenuConfig: Array<MenuItem> = [
  {
    pages: [
      {
        heading: "Пользователи",
        route: "/user/list",
        svgIcon: "/media/icons/duotune/communication/com006.svg",
        fontIcon: "bi-person",
      },
    ],
  },
];

export default MainMenuConfig;
