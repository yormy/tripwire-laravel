import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Tripwire Laravel",
  description: "A extensive web application firewall (waf) to stock hackers in their tracks",
  base: '/tripwire-laravel/',
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
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
          { text: 'Need Support?', link: '/v1/support-me' },
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
          { text: 'Request Validator - todo', link: '/v1/guide/advanced/`setup/checksum' },
          { text: 'Remove blocks -todo', link: '/v1/guide/advanced/setup/reset' },
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
        ]
      },
      {
        text: 'Customization - todo',
        items: [
          { text: 'Publish Config', link: '/v1/guide/customization/config' },
          { text: 'Notifications', link: '/v1/guide/customization/notifications' },
          { text: 'Block response', link: '/v1/guide/customization/block' },
          { text: 'Reject response', link: '/v1/guide/customization/reject' },
          { text: 'Wire Groups', link: '/v1/guide/customization/wire-groups' },
          { text: 'Views', link: '/v1/guide/customization/views' },
          { text: 'Translations', link: '/v1/guide/customization/translations' },
          { text: 'Database', link: '/v1/guide/customization/database' },

          { text: 'Wires -todo', link: '/v1/guide/customization/wires' },
          { text: 'Browser fingerprint -todo', link: '/v1/guide/advanced/browser-fingerprint' },
          { text: 'Customization -todo', link: '/v1/guide/customization/customization' },
        ]
      },
      {
        text: 'References',
        items: [
          {
            text: 'Wires',
            items: [
              { text: 'General-todo', link: '/v1/guide/configuration/wires-general' },
              { text: 'Request-todo', link: '/v1/guide/configuration/wires-request' }
            ]
          },
          { text: 'Events-todo', link: '/v1/guide/references/events' },
          { text: 'All Wires-todo', link: '/v1/guide/references/wires' },
          { text: 'Response Types', items: [
              { text: 'Json Response', link: '/v1/guide/references/json-response' },
              { text: 'Html Response', link: '/v1/guide/references/html-response' },
            ]},
        ]
      },
      { text: 'Debugging-todo', link: '/v1/guide/configuration/debug' },
      { text: 'Support', link: '/v1/support-me' }

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
