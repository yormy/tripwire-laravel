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
          { text: 'Blockers', link: '/v1/guide/advanced/setup/blockers' },
          { text: 'Exception Inspector', link: '/v1/guide/advanced/setup/exceptions' },
          { text: 'Request Validator - todo', link: '/v1/guide/setup/checksum' },
        ]
      },
      {
        text: 'Advanced Configuration',
        items: [
          { text: 'Removing blocks  -todo', link: '/v1/guide/advanced/reset' },
          { text: 'Configuration -todo', link: '/v1/guide/advanced/configuration' },
          { text: 'Blockers - todo', link: '/v1/guide/advanced/blockers' },


          { text: 'Wires -todo', link: '/v1/guide/advanced/wires' },
          { text: 'Training mode', link: '/v1/guide/advanced/configuration/training-mode' },
          { text: 'Debug mode', link: '/v1/guide/advanced/configuration/debug-mode' },
          { text: 'Browser fingerprint -todo', link: '/v1/guide/advanced/browser-fingerprint' },
        ]
      },
      {
        text: 'Use cases',
        items: [
          { text: 'Basic setup -todo', link: '/..' },
          { text: 'Xss prevention-todo', link: '/-examples' },
        ]
      },
      {
        text: 'Customization',
        items: [
          { text: 'Customization -todo', link: '/v1/guide/customization/customization' },
          { text: 'Notifications', link: '/v1/guide/customization/notifications' },
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
          { text: 'Events-todo', link: '/v1/guide/references/events' }
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
