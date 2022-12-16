export interface MenuItem {
  heading?: string;
  sectionTitle?: string;
  route?: string;
  pages?: Array<MenuItem>;
  svgIcon?: string;
  fontIcon?: string;
  sub?: Array<MenuItem>;
}

const MainMenuConfig: Array<any> = [
  {
      'Teacher': {
          pages: [
              {
                  heading: "Клиенты",
                  route: "/user/list",
                  svgIcon: "/media/icons/duotune/communication/com006.svg",
                  fontIcon: "bi-person",
              },{
                  heading: "Задачи",
                  route: "/task-category/list",
                  svgIcon: "/media/icons/duotune/files/folder.svg",
                  fontIcon: "default",
              },
          ],
      },
      'Client': {
          pages: [
              {
                  heading: "Клиенты",
                  route: "/user/list",
                  svgIcon: "/media/icons/duotune/communication/com006.svg",
                  fontIcon: "bi-person",
              },{
                  heading: "Задачи",
                  route: "/task-category/list",
                  svgIcon: "/media/icons/duotune/files/folder.svg",
                  fontIcon: "default",
              },
          ],
      },
      'Guest': {
          pages: [
              {
                  heading: "Стартовая страница",
                  route: "/greetings",
                  svgIcon: "/media/icons/duotune/communication/com006.svg",
                  fontIcon: "default",
              },
          ],
      }
  },
];

export default MainMenuConfig;
