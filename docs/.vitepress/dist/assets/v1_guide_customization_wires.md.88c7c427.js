import{_ as e,o as t,c as s,V as a}from"./chunks/framework.5f0cae6b.js";const _=JSON.parse('{"title":"Wires","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/customization/wires.md","filePath":"v1/guide/customization/wires.md"}'),i={name:"v1/guide/customization/wires.md"},r=a(`<h1 id="wires" tabindex="-1">Wires <a class="header-anchor" href="#wires" aria-label="Permalink to &quot;Wires&quot;">​</a></h1><p>Wires are configured in the <code>tripwire_wires.php</code><a href="./../references/wires.html">See how to setup a specific wire</a></p><h2 id="changing-the-ruleset" tabindex="-1">Changing the ruleset <a class="header-anchor" href="#changing-the-ruleset" aria-label="Permalink to &quot;Changing the ruleset&quot;">​</a></h2><p>When you change the ruleset it is good to manually run all tests again to test if their are no suddenly false positives or false negatives added.</p><p>Run the following command to test your config and changes to make sure all test and checkers remain working best is to have your application Test extensive to run a whole list of possible attack vectors to see if they are all blocked</p><p>Suggestion:</p><ul><li>phpunit env set to use sqllite in memory</li></ul><p>Or</p><ul><li>use a real database</li><li>set tripwire debugMode to true so you can see what input was triggered by what rule</li></ul><div class="language-"><button title="Copy Code" class="copy"></button><span class="lang"></span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#A6ACCD;">./vendor/bin/phpunit ./vendor/yormy/tripwire-laravel/src/Tests --testdox --testsuite Main</span></span>
<span class="line"><span style="color:#A6ACCD;">./vendor/bin/phpunit ./vendor/yormy/tripwire-laravel/src/Tests --testdox --testsuite Extensive</span></span></code></pre></div><p>You can see a list of all wires...</p>`,11),o=[r];function n(l,c,p,u,d,h){return t(),s("div",null,o)}const m=e(i,[["render",n]]);export{_ as __pageData,m as default};
