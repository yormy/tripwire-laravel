import{_ as s,o as a,c as n,V as e}from"./chunks/framework.5f0cae6b.js";const C=JSON.parse('{"title":"Basic setup","description":"","frontmatter":{},"headers":[],"relativePath":"v1/guide/basic/setup.md","filePath":"v1/guide/basic/setup.md"}'),l={name:"v1/guide/basic/setup.md"},o=e(`<h1 id="basic-setup" tabindex="-1">Basic setup <a class="header-anchor" href="#basic-setup" aria-label="Permalink to &quot;Basic setup&quot;">​</a></h1><p>There are a few steps involved to setup Tripwire.</p><ul><li>Malicious requests need to be recognized and responded to</li><li>Malicious users need to be blocked</li></ul><h2 id="definitions" tabindex="-1">Definitions <a class="header-anchor" href="#definitions" aria-label="Permalink to &quot;Definitions&quot;">​</a></h2><h3 id="log" tabindex="-1">Log <a class="header-anchor" href="#log" aria-label="Permalink to &quot;Log&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: Log</p><p>Every request that is recognized as a hack attempt is logged regardless it blocks the rest of the request or not.</p></div><h3 id="block" tabindex="-1">Block <a class="header-anchor" href="#block" aria-label="Permalink to &quot;Block&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: Block</p><p>A block prevents a certain user or Ip from accessing your site. As long a the block is valid no requests will continue to your site. This block is only temporarily and will be removed after a few seconds. However if the same user/ip continues their attempts and gets blocked again the time will increase exponentially. A block can be based on a ip address, user id, and or browser-fingerprint (if supplied by your frontend)</p></div><h3 id="" tabindex="-1"><a class="header-anchor" href="#" aria-label="Permalink to &quot;&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: Reject</p><p>When a request is suspicious it is rejected and this could lead eventually to a block</p></div><h3 id="wire" tabindex="-1">Wire <a class="header-anchor" href="#wire" aria-label="Permalink to &quot;Wire&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: Wire</p><p>A checked that parses the request to see it if violates certain rules. If a wire is triggered it is considered as a hack attempt</p></div><h3 id="honeypot-wire" tabindex="-1">Honeypot Wire <a class="header-anchor" href="#honeypot-wire" aria-label="Permalink to &quot;Honeypot Wire&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: Honeypot wire</p><p>A honeypot is a security mechanism that creates a virtual trap to lure attackers. When Tripwire recognizes that certain illegal fields are filled in, then we know this is not a normal user and an action will be taken</p></div><h3 id="attackscore" tabindex="-1">AttackScore <a class="header-anchor" href="#attackscore" aria-label="Permalink to &quot;AttackScore&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: AttackScore</p><p>Every wire has a attackScore (either specified or default), the higher the score the more severe and certain you are that this is a malicious request.</p></div><h3 id="punish" tabindex="-1">Punish <a class="header-anchor" href="#punish" aria-label="Permalink to &quot;Punish&quot;">​</a></h3><div class="tip custom-block"><p class="custom-block-title">Definition: Punish</p><p>When the user attempts too many times, the user is blocked (or punished).</p></div><h2 id="recognizing-and-blocking-malicious-requests" tabindex="-1">Recognizing and blocking malicious requests <a class="header-anchor" href="#recognizing-and-blocking-malicious-requests" aria-label="Permalink to &quot;Recognizing and blocking malicious requests&quot;">​</a></h2><p>Recognizing malicious requests happen through the so called wires. When a wire is tripped, actions will be taken. These actions can be defined (see configuration). But we first need to define which wires to use. There are many different wires and wire groups but start with the basics</p><p>In your <code>kernel.php</code> make the following additions</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight has-focused-lines"><code><span class="line has-focus"><span style="color:#C792EA;">protected</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">middlewareGroups </span><span style="color:#89DDFF;">=</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span><span style="color:#A6ACCD;"> </span></span>
<span class="line has-focus"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">web</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">=&gt;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span><span style="color:#A6ACCD;"> </span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">App</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Http</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Middleware</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">EncryptCookies</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Illuminate</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Cookie</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Middleware</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">AddQueuedCookiesToResponse</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Illuminate</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Session</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Middleware</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">StartSession</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line has-focus"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#676E95;font-style:italic;">// will block malicious ips/users</span></span>
<span class="line has-focus"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;">  </span><span style="color:#676E95;font-style:italic;">// will recognize and action on malicious requests</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">...</span></span></code></pre></div><p>If you also have an api section, you need to add it there too</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight has-focused-lines"><code><span class="line has-focus"><span style="color:#C792EA;">protected</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">middlewareGroups </span><span style="color:#89DDFF;">=</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span><span style="color:#A6ACCD;"> </span></span>
<span class="line has-focus"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">api</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">=&gt;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span><span style="color:#A6ACCD;"> </span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Laravel</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Sanctum</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Http</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Middleware</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">EnsureFrontendRequestsAreStateful</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Illuminate</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Routing</span><span style="color:#89DDFF;">\\</span><span style="color:#A6ACCD;">Middleware</span><span style="color:#89DDFF;">\\</span><span style="color:#FFCB6B;">SubstituteBindings</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line has-focus"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span><span style="color:#A6ACCD;"> </span><span style="color:#676E95;font-style:italic;">// will block malicious ips/users</span></span>
<span class="line has-focus"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;">  </span><span style="color:#676E95;font-style:italic;">// will recognize and action on malicious requests</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">],</span></span></code></pre></div><div class="warning custom-block"><p class="custom-block-title">Order is important</p><p>First include your blockhandler (ie: TripwireBlockHandlerAll::class) and then your tripwires (ie &#39;tripwire.main). This will ensure that no blocked user is getting trough to your tripwire handlers. This makes the response faster</p></div><h1 id="blocking-users-or-blocking-early" tabindex="-1">Blocking Users or Blocking Early <a class="header-anchor" href="#blocking-users-or-blocking-early" aria-label="Permalink to &quot;Blocking Users or Blocking Early&quot;">​</a></h1><p>There is a fundamental choice you must make in when to block requests</p><ul><li>Option 1: Block as soon as possible in the request cycle</li><li>Option 2 (recommended): Block a userId if the request came from a recognized user.</li></ul><h2 id="option-1-block-as-soon-as-possible" tabindex="-1">Option 1: Block as soon as possible <a class="header-anchor" href="#option-1-block-as-soon-as-possible" aria-label="Permalink to &quot;Option 1: Block as soon as possible&quot;">​</a></h2><p>This will block a request as soon as possible, but that includes also before a userId is known. Consequences, there will be no block on userId, only on IP or browserFingerprint</p><h3 id="setup" tabindex="-1">Setup <a class="header-anchor" href="#setup" aria-label="Permalink to &quot;Setup&quot;">​</a></h3><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#C792EA;">protected</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">middlewareGroups </span><span style="color:#89DDFF;">=</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#A6ACCD;">  </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">web</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">=&gt;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#89DDFF;">        </span><span style="color:#676E95;font-style:italic;">// ... rest of the middleware</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">]</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">api</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">=&gt;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#89DDFF;">        </span><span style="color:#676E95;font-style:italic;">// ... rest of the middleware</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">]</span></span></code></pre></div><p>or even just in the root middleware</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#C792EA;">protected</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">middleware </span><span style="color:#89DDFF;">=</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#89DDFF;">    </span><span style="color:#676E95;font-style:italic;">// ... rest of the middleware</span></span></code></pre></div><h2 id="option-2-recommended-block-a-userid-if-present" tabindex="-1">Option 2 (recommended) : Block a userId if present <a class="header-anchor" href="#option-2-recommended-block-a-userid-if-present" aria-label="Permalink to &quot;Option 2 (recommended) : Block a userId if present&quot;">​</a></h2><p>Consequences: the block will happen a bit later in the request cycle, but if a user it blocked then if they use a different IP they are still blocked on a userId level This is a more secure block</p><div class="language-php"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki material-theme-palenight"><code><span class="line"><span style="color:#C792EA;">protected</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">$</span><span style="color:#A6ACCD;">middlewareGroups </span><span style="color:#89DDFF;">=</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#A6ACCD;">  </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">web</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">=&gt;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#89DDFF;">        </span><span style="color:#676E95;font-style:italic;">// ... middleware for authentications</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#89DDFF;">        </span><span style="color:#676E95;font-style:italic;">// ... rest of the middleware</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">]</span></span>
<span class="line"></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">api</span><span style="color:#89DDFF;">&#39;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">=&gt;</span><span style="color:#A6ACCD;"> </span><span style="color:#89DDFF;">[</span></span>
<span class="line"><span style="color:#89DDFF;">        </span><span style="color:#676E95;font-style:italic;">// ... middleware for authentications</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#FFCB6B;">TripwireBlockHandlerAll</span><span style="color:#89DDFF;">::</span><span style="color:#F78C6C;">class</span><span style="color:#89DDFF;">,</span></span>
<span class="line"><span style="color:#A6ACCD;">        </span><span style="color:#89DDFF;">&#39;</span><span style="color:#C3E88D;">tripwire.main</span><span style="color:#89DDFF;">&#39;</span></span>
<span class="line"><span style="color:#89DDFF;">        </span><span style="color:#676E95;font-style:italic;">// ... rest of the middleware</span></span>
<span class="line"><span style="color:#A6ACCD;">    </span><span style="color:#89DDFF;">]</span></span></code></pre></div>`,37),p=[o];function t(r,c,i,D,y,F){return a(),n("div",null,p)}const u=s(l,[["render",t]]);export{C as __pageData,u as default};
