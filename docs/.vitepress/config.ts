import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Tripwire Laravel",
  description: "A extensive web application firewall (waf) to stock hackers in their tracks",
  //base: '/tripwire-laravel/',
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    search: {
      provider: 'local'
    },
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/v1/guide/what-is-tripwire' },
    ],

    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'What is Tripwire', link: '/v1/guide/what-is-tripwire' },
          { text: 'Why Support me', link: '/v1/support-me' },
          { text: 'Definitions', link: '/v1/definitions.md' },
        ]
      },
      {
        text: 'Getting Started',
        items: [
          { text: 'Installation', link: '/v1/guide/installation' },
          { text: 'Setup', link: '/v1/guide/basic/setup' },
          { text: 'Configuration', link: '/v1//guide/basic/configuration' },
          { text: 'Tips', link: '/v1//guide/basic/tips' },
        ]
      },
      {
        text: 'Advanced Configuration',
        items: [
          { text: 'Resetting blocks', link: '/v1/guide/configuration/reset' },
          { text: 'Configuration', link: '/v1/guide/advanced/configuration' },
          { text: 'Blockers', link: '/v1/guide/advanced/blockers' },
          { text: 'Checksum', link: '/v1/guide/advanced/checksum' },
          { text: 'Exception', link: '/v1/guide/advanced/exceptions' },
          { text: 'Wires', link: '/v1/guide/advanced/wires' },
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
          { text: 'Customization', link: '/v1/guide/customization/customization' },
          { text: 'Notifications', link: '/v1/guide/customization/notifications' },
        ]
      },
      {
        text: 'References',
        items: [
          {
            text: 'Wires',
            items: [
              { text: 'General', link: '/v1/guide/configuration/wires-general' },
              { text: 'Request', link: '/v1/guide/configuration/wires-request' }
            ]
          },
          { text: 'Events', link: '/v1/guide/configuration/events' }
        ]
      },
      { text: 'Debugging', link: '/v1/guide/configuration/debug' },
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
