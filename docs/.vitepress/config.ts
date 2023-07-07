import { defineConfig } from 'vitepress'

export default defineConfig({
  title: "Tripwire",
  description: "A extensive web application firewall (waf) to stock hackers in their tracks",
  base: '/tripwire-laravel/',
  head: [
    ['link', { rel: "apple-touch-icon", sizes: "180x180", href: "/assets/images/apple-touch-icon.png"}],
    ['link', { rel: "icon", type: "image/png", sizes: "32x32", href: "/assets/images/favicon-32x32.png"}],
    ['link', { rel: "icon", type: "image/png", sizes: "16x16", href: "/assets/images/favicon-16x16.png"}],
  ],
  themeConfig: {
    search: {
      provider: 'local'
    },
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/v1/introduction/what-is-tripwire' },
    ],

    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'What is Tripwire', link: '/v1/introduction/what-is-tripwire' },
          { text: 'Definitions', link: '/v1/definitions.md' },
          { text: 'Need Support?', link: '/general/support/support-me' },
        ]
      },
      {
        text: 'Getting Started',
        items: [
          { text: 'Installation', link: '/v1/guide/installation' },
          { text: 'Basic Setup', link: '/v1/guide/basic/setup' },
          { text: 'Basic Configuration', link: '/v1//guide/basic/configuration' },
          { text: 'Tips', link: '/v1//guide/basic/tips' },
          { text: 'Frontend', link: '/v1//guide/basic/frontend' },
        ]
      },
      {
        text: 'Advanced Setup',
        items: [
          { text: 'Wires', link: '/v1/guide/advanced/setup/wires' },
          { text: 'Blockers', link: '/v1/guide/advanced/setup/blockers' },
          { text: 'Exception Inspector', link: '/v1/guide/advanced/setup/exceptions' },
          { text: 'Remove blocks -todo', link: '/v1/guide/advanced/setup/reset' },
          { text: 'Remove Tripwires on Routes', link: '/v1/guide/advanced/setup/routes' },
        ]
      },
      {
        text: 'Advanced Configuration',
        items: [
          { text: 'Config files', link: '/v1/guide/advanced/configuration/config-files' },
          { text: 'Options', link: '/v1/guide/advanced/configuration/options' },
          { text: 'Training mode', link: '/v1/guide/advanced/configuration/training-mode' },
          { text: 'Debug mode', link: '/v1/guide/advanced/configuration/debug-mode' },
          { text: 'Block Response', link: '/v1/guide/advanced/configuration/block-response' },
          { text: 'Reject Response', link: '/v1/guide/advanced/configuration/reject-response' },
          { text: 'Browser fingerprinting', link: '/v1/guide/advanced/configuration/browser-fingerprint' },
        ]
      },
      {
        text: 'General Customization - todo',
        items: [
          { text: 'Publish Config', link: '/v1/guide/customization/config' },
          { text: 'Notifications', link: '/v1/guide/customization/notifications' },
          { text: 'Block response', link: '/v1/guide/customization/block' },
          { text: 'Reject response', link: '/v1/guide/customization/reject' },
          { text: 'Wire Groups', link: '/v1/guide/customization/wire-groups' },
          { text: 'Views', link: '/v1/guide/customization/views' },
          { text: 'Translations', link: '/v1/guide/customization/translations' },
          { text: 'Database', link: '/v1/guide/customization/database' },

          { text: 'Service Helpers', link: '/v1/guide/customization/services' },
          { text: 'Encryption - todo', link: '/v1/guide/customization/encryption' },

          { text: 'Punish', link: '/v1/guide/customization/punish' },
          { text: 'Urls', link: '/v1/guide/customization/urls' },
          { text: 'Logging', link: '/v1/guide/customization/logging' },
          { text: 'Ignores', link: '/v1/guide/customization/ignores' },
          { text: 'Models', link: '/v1/guide/customization/models' },

          { text: 'Browser fingerprint -todo', link: '/v1/guide/advanced/browser-fingerprint' },
        ]
      },
      {
        text: 'Wire Customization - todo',
        items: [
          { text: 'Wires -todo', link: '/v1/guide/customization/wires' },
        ]
      },
      {
        text: 'References',
        items: [
          {
            text: 'Wires',
            items: [
              { text: 'General-todo', link: '/v1/guide/references/wires-general' },
              { text: 'Request-todo', link: '/v1/guide/references/wires-request' }
            ]
          },
          { text: 'Events-todo', link: '/v1/guide/references/events' },
          { text: 'All Wires-todo', link: '/v1/guide/references/wires' },
          { text: 'Regex for wires', link: '/v1/guide/references/wires/regex' },
          { text: 'Response Types', items: [
              { text: 'Json Response', link: '/v1/guide/references/json-response' },
              { text: 'Html Response', link: '/v1/guide/references/html-response' },
            ]},
        ]
      },
      { text: 'Contributing', items: [
        { text: 'Report Security Issues', link: '/general/report_security' },
        { text: 'License', link: '/general/license' },
        { text: 'Change log', link: '/general/changelog' },
        { text: 'Contributing', link: '/general/contributing' },
        { text: 'Code of Conduct', link: '/general/code_of_conduct' },
        { text: 'Credits', link: '/general/credits' },
      ]},

      { text: 'Contact', items: [
          { text: 'Contact', link: '/general/contact' },
          { text: 'Support', link: '/general/support/support-me' },
          { text: 'Donations', link: '/general/support/donations' },
        ]},

    ],

    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright © 2022 to present Yormy'
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/yormy/tripwire-laravel' }
    ]
  }
})
