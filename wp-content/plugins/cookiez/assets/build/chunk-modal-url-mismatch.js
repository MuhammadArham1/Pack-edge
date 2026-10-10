"use strict";(globalThis.webpackChunkcookiez=globalThis.webpackChunkcookiez||[]).push([[7963],{11549(e,o,n){n.d(o,{UrlMismatchModal:()=>j});var i=n(42645),t=n(78048),c=n(50602),r=n(77374),a=n(74456),s=n(76656),l=n(34883),d=n(85848),h=n(95231),m=n(38516),x=n(19258),p=n(95830),u=n(96914),g=n(8617),A=n(86087),y=n(27723),_=n(93832),k=n(10790);const j=()=>{const{isOpen:e,close:o}=(0,x.A)(),n=(0,m.mu)(),[t,h]=(0,A.useState)(!1),{isElementorOne:j}=(0,g.F)();return(0,k.jsxs)(k.Fragment,{children:[(0,k.jsxs)(w,{open:e,onClose:o,maxWidth:"lg",children:[(0,k.jsx)(l.A,{logo:!1,children:(0,k.jsx)(d.A,{variant:"subtitle1",component:"h2",children:(0,y.__)("Fix mismatched URL","cookiez")})}),(0,k.jsx)(s.A,{dividers:!0,children:(0,k.jsxs)(z,{children:[(0,k.jsx)(d.A,{variant:"h4",color:"text.primary",component:"h3",children:(0,y.__)("Choose how to reconnect Cookiez to your site","cookiez")}),(0,k.jsx)(f,{variant:"body1",color:"text.secondary",component:"p",children:(0,y.__)("Your license key does not match your current domain, causing a mismatch. This is most likely due to a change in the domain URL of your site.","cookiez")}),(0,k.jsxs)(v,{children:[(0,k.jsxs)(C,{children:[(0,k.jsx)(d.A,{variant:"h6",component:"h4",marginBottom:3,children:(0,y.__)("Update the connected URL","cookiez")}),(0,k.jsx)(d.A,{variant:"body1",component:"p",color:"text.secondary",marginBottom:3,children:(0,y.__)("For cases where you're moving the same site from staging to production or changing from HTTP to HTTPS.","cookiez")}),(0,k.jsx)(c.A,{variant:"contained",onClick:async()=>{if(j)window.location.href=u.iz;else{try{await p.A.initConnect("update"),window.location.reload()}catch{n.error((0,y.__)("An error occurred.","cookiez"))}o()}},children:(0,y.__)("Update URL","cookiez")})]}),(0,k.jsxs)(C,{children:[(0,k.jsx)(d.A,{variant:"h6",component:"h4",marginBottom:3,children:(0,y.__)("Connect the URL as a new site","cookiez")}),(0,k.jsx)(d.A,{variant:"body1",component:"p",color:"text.secondary",marginBottom:3,children:(0,y.__)("For when you want to connect the plugin to a new site entirely—deleting the previous history.","cookiez")}),(0,k.jsx)(c.A,{variant:"contained",onClick:()=>h(!0),children:(0,y.__)("Connect new site","cookiez")})]})]})]})})]}),t&&(0,k.jsxs)(r.A,{open:!0,onClose:()=>h(!1),maxWidth:"xs",fullWidth:!0,children:[(0,k.jsx)(l.A,{logo:(0,k.jsx)(i.A,{color:"error"}),children:(0,k.jsx)(d.A,{variant:"subtitle1",component:"h2",children:(0,y.__)("Are you sure you want to connect as a new site?","cookiez")})}),(0,k.jsx)(s.A,{children:(0,k.jsx)(d.A,{variant:"body1",component:"p",children:(0,y.__)("Connecting as a new site will delete data related to the current site.","cookiez")})}),(0,k.jsxs)(a.A,{children:[(0,k.jsx)(c.A,{color:"secondary",onClick:()=>h(!1),children:(0,y.__)("Cancel","cookiez")}),(0,k.jsx)(c.A,{variant:"contained",color:"error",onClick:async()=>{if(j)window.location.href=u.iz;else{try{h(!1),await p.A.clearSession(),window.location.href=(0,_.addQueryArgs)(window.location.href,{action:"connect"})}catch{n.error((0,y.__)("An error occurred.","cookiez"))}o()}},children:(0,y.__)("Connect","cookiez")})]})]})]})},w=(0,h.I)(r.A)`
	margin-inline-start: 10%;
`,z=(0,h.I)(t.A)`
	display: flex;
	flex-direction: column;
	align-items: center;
	padding: ${({theme:e})=>e.spacing(5,8)};
`,f=(0,h.I)(d.A)`
	text-align: center;
	width: 70%;
	margin-block-start: ${({theme:e})=>e.spacing(1)};
`,v=(0,h.I)(t.A)`
	display: flex;
	justify-content: center;
	gap: ${({theme:e})=>e.spacing(3)};
	margin: ${({theme:e})=>e.spacing(10,0)};
`,C=(0,h.I)(t.A)`
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	width: 38%;
	padding: ${({theme:e})=>e.spacing(5)};
	border: 1px solid ${({theme:e})=>e.palette.divider};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	text-align: center;
`}}]);