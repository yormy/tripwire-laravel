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
        ]
      },
      {
        text: 'Advanced Configuration',
        items: [
          { text: 'Resetting blocks', link: '/v1/guide/configuration/reset' },
          { text: 'Setup', link: '/v1/guide/basic/setup' },
          { text: 'Configuration', link: '/v1//guide/basic/configuration' },
        ]
      },
      {
        text: 'Use cases',
        items: [
          { text: 'Basic setup', link: '/..' },
          { text: 'Xss prevention', link: '/-examples' },
        ]
      },
      {
        text: 'Customization',
        items: [
          { text: 'Customization', link: '/m-examples' },
          { text: 'Debugging', link: '/-examples' }
        ]
      }

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
