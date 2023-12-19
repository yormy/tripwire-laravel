import{_ as a,o as s,c as l,V as e}from"./chunks/framework.5f0cae6b.js";const h=JSON.parse('{"title":"","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/references/wires/_filters.md","filePath":"v1/guide/references/wires/_filters.md"}'),o={name:"v1/guide/references/wires/_filters.md"},p=e('<h2 id="filters" tabindex="-1">Filters <a class="header-anchor" href="#filters" aria-label="Permalink to &quot;Filters&quot;">​</a></h2><p>This specifies what to allow and what to block</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">filters</span><span style="color:#89DDFF;">(</span><span style="color:#FFCB6B;">AllowBlockFilterConfig</span><span style="color:#89DDFF;">::</span><span style="color:#82AAFF;">make</span><span style="color:#89DDFF;">()-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">allow-this</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">block-this</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">]));</span></span></code></pre></div><h3 id="example-exception" tabindex="-1">Example: Exception <a class="header-anchor" href="#example-exception" aria-label="Permalink to &quot;Example: Exception&quot;">​</a></h3><p>allow must be at least [&#39;*&#39;] to allow all</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([])</span></span></code></pre></div><h3 id="example-allowed" tabindex="-1">Example: Allowed <a class="header-anchor" href="#example-allowed" aria-label="Permalink to &quot;Example: Allowed&quot;">​</a></h3><p><code>firefox</code> is allowed</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">firefox</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([])</span></span></code></pre></div><h3 id="example-blocked" tabindex="-1">Example: Blocked <a class="header-anchor" href="#example-blocked" aria-label="Permalink to &quot;Example: Blocked&quot;">​</a></h3><p><code>brave</code> is blocked</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">*</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">brave</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])</span></span></code></pre></div><h3 id="example-not-blocked" tabindex="-1">Example: Not Blocked <a class="header-anchor" href="#example-not-blocked" aria-label="Permalink to &quot;Example: Not Blocked&quot;">​</a></h3><p><code>firebrave</code> is not blocked, so allowed</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">*</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([])</span></span></code></pre></div><h3 id="example-not-blocked-1" tabindex="-1">Example: Not Blocked <a class="header-anchor" href="#example-not-blocked-1" aria-label="Permalink to &quot;Example: Not Blocked&quot;">​</a></h3><p><code>Chrome</code> is not blocked, so allowed</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">*</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">brave</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])</span></span></code></pre></div><h3 id="example-allowed-and-blocked" tabindex="-1">Example: Allowed and Blocked <a class="header-anchor" href="#example-allowed-and-blocked" aria-label="Permalink to &quot;Example: Allowed and Blocked&quot;">​</a></h3><p><code>firebrave</code> is both allowed and blocked, so allowed</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">firebrave</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">firebrave</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])</span></span></code></pre></div><h3 id="example-unspecified" tabindex="-1">Example: Unspecified <a class="header-anchor" href="#example-unspecified" aria-label="Permalink to &quot;Example: Unspecified&quot;">​</a></h3><p><code>firebrave</code> is both not as allowed and not as blocked The return depends on where it is used.</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#89DDFF;">-&gt;</span><span style="color:#82AAFF;">allow</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">firebrave</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])-&gt;</span><span style="color:#82AAFF;">block</span><span style="color:#89DDFF;">([</span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">firebrave</span><span style="color:#89DDFF;">&#39;</span><span style="color:#89DDFF;">])</span></span></code></pre></div>',24),n=[p];function t(c,r,F,i,d,D){return s(),l("div",null,n)}const b=a(o,[["render",t]]);export{h as __pageData,b as default};