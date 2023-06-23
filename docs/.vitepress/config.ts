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
      { text: 'Guide', link: '/guide/what-is-tripwire' },
      { text: 'Examples', link: '/examples' }
    ],

    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'Support me', link: '/markdown-examples' },
          { text: 'What is Tripwire', link: '/markdown-examples' },
          { text: 'Getting Started', link: '/api-examples' }
        ]
      },
      {
        text: 'Getting Started',
        items: [
          { text: 'Installation', link: '/markdown-examples' },
          { text: 'Configuration', link: '/markdown-examples' },
        ]
      },
      {
        text: 'Use cases',
        items: [
          { text: 'Basic setup', link: '/markdown-examples' },
          { text: 'Xss prevention', link: '/markdown-examples' },
        ]
      },
      {
        text: 'Customization',
        items: [
          { text: 'Customization', link: '/markdown-examples' },
          { text: 'Debugging', link: '/api-examples' }
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
