"use strict";(globalThis.webpackChunkcookiez=globalThis.webpackChunkcookiez||[]).push([[8101],{56603(e,o,i){i.r(o),i.d(o,{default:()=>A});var t=i(50602),n=i(77374),s=i(74456),r=i(76656),a=i(34883),c=i(33022),l=i(95726),d=i(57936),u=i(62646),h=i(85848),g=i(95231),k=i(38516),m=i(95830),p=i(96914),_=i(86087),y=i(27723),b=i(10790);const x=(0,g.I)(a.A)`
	position: relative;

	> div {
		padding: ${({theme:e})=>`\n\t\t\t${e.spacing(2)} \n\t\t\t${e.spacing(4.5)}\n\t\t\t${e.spacing(2)}\n\t\t\t${e.spacing(2.75)}\n\t\t`};
		min-height: auto;
	}
`,C=(0,g.I)(h.A)`
	line-height: 1.5;
`,w=(0,g.I)(l.A)`
	padding-inline-start: ${({theme:e})=>e.spacing(2)};
	list-style-type: disc;
`,z=(0,g.I)(d.A)`
	padding-block: 0;
	min-height: auto;
`,j=(0,g.I)(u.A)`
	display: list-item;
	color: ${({theme:e})=>e.palette.text.secondary};
	margin: 0;
`,v=(0,g.I)(s.A)`
	padding: ${({theme:e})=>e.spacing(2,3)};
`,A=({onClose:e})=>{const o=(0,k.mu)(),[i,s]=(0,_.useState)(!1),a=()=>{(()=>{const e={isMigrationPopupDismissed:!0};window.cookiezSettingsData?.settings&&Object.assign(window.cookiezSettingsData.settings,e),m.A.updatePluginSettings(e)})(),e(),o.hint((0,b.jsxs)(b.Fragment,{children:[(0,y.__)("You can always migrate your subscriptions from the","cookiez")," ",(0,b.jsx)(c.A,{href:p.$5,underline:"always",children:(0,y.__)("Tool Manager","cookiez")}),"."]}))};return(0,b.jsxs)(n.A,{open:!0,onClose:a,maxWidth:"sm",fullWidth:!0,"aria-labelledby":"migration-dialog-title",children:[(0,b.jsx)(x,{logo:!1,children:(0,b.jsx)(C,{variant:"subtitle1",component:"h2",fontWeight:600,id:"migration-dialog-title",children:(0,y.__)("Move Cookie Consent to Elementor One","cookiez")})}),(0,b.jsxs)(r.A,{children:[(0,b.jsx)(h.A,{variant:"body2",color:"text.secondary",marginBlockEnd:2,children:(0,y.__)("Cookie Consent is currently active with its own subscription. You also have an Elementor One subscription for this site.","cookiez")}),(0,b.jsx)(h.A,{variant:"body2",color:"text.secondary",marginBlockEnd:1,children:(0,y.__)("If you switch to Elementor One:","cookiez")}),(0,b.jsxs)(w,{dense:!0,disablePadding:!0,children:[(0,b.jsx)(z,{disableGutters:!0,children:(0,b.jsx)(j,{primary:(0,y.__)("Your current Cookie Consent subscription will be deactivated","cookiez")})}),(0,b.jsx)(z,{disableGutters:!0,children:(0,b.jsx)(j,{primary:(0,y.__)("Cookie Consent will use your shared Elementor One credits","cookiez")})})]})]}),(0,b.jsxs)(v,{children:[(0,b.jsx)(t.A,{color:"secondary",variant:"outlined",onClick:a,disabled:i,children:(0,y.__)("Not now","cookiez")}),(0,b.jsx)(t.A,{variant:"contained",onClick:async()=>{s(!0);try{const i=await m.A.migrateToOne();i?.isMigrated?(e(),o.success((0,y.__)("Cookie Consent has successfully moved to your One subscription.","cookiez")),setTimeout(()=>{window.location.reload()},1500)):(s(!1),o.error((0,y.__)("Cookie Consent could not move to your One subscription. Please try again.","cookiez")))}catch(e){s(!1);const i=e,t=i?.message??(0,y.__)("Unknown error","cookiez");o.error(`${(0,y.__)("Cookie Consent could not move to your One subscription.","cookiez")} ${(0,y.__)("Error:","cookiez")} ${t}`)}},disabled:i,children:(0,y.__)("Move to One","cookiez")})]})]})}}}]);