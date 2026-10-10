"use strict";(globalThis.webpackChunkcookiez=globalThis.webpackChunkcookiez||[]).push([[5864],{55076(e,i,o){o.d(i,{A:()=>p});var l=o(78048),n=o(93248),t=o(55984),s=o(85848),a=o(95231),r=o(10790);const c=(0,a.I)(l.A)`
	display: flex;
	flex-direction: column;
	align-items: flex-start;
`,d=(0,a.I)(s.A)`
	margin-block-end: ${({theme:e})=>e.spacing(1.5)};

	font-weight: ${({theme:e})=>e.typography.fontWeightBold};
`,p=({value:e,label:i,description:o,disabled:l})=>(0,r.jsx)(n.A,{value:e,disabled:l,control:(0,r.jsx)(t.A,{size:"small",color:"info",sx:{padding:0,marginInlineEnd:1}}),label:(0,r.jsxs)(c,{children:[(0,r.jsx)(d,{variant:"subtitle1",component:"p",children:i}),o]}),sx:{marginInline:0,alignItems:o?"flex-start":"center",...l&&{opacity:.5}}})},79729(e,i,o){o.d(i,{FI:()=>n.A,YJ:()=>s.A,si:()=>l.A,t5:()=>t.A});var l=o(55076),n=o(16775),t=o(37565),s=o(94494)},15613(e,i,o){o.r(i),o.d(i,{default:()=>$});var l=o(50602),n=o(76992),t=o(77374),s=o(74456),a=o(34883),r=o(95231),c=o(89896),d=o(79729),p=o(12069),h=o(92810),g=o(86752),x=o(84162),m=o(85848),u=o(15679),b=o(22863),j=o(79011),k=o(86087),v=o(27723),f=o(10790);const _=(0,r.I)(g.A)`
	display: flex;
	flex-direction: column;
`,y=(0,r.I)(x.A)`
	gap: ${({theme:e})=>e.spacing(1)};

	& .MuiFormControlLabel-root {
		align-items: center;

		gap: ${({theme:e})=>e.spacing(1)};
		padding: ${({theme:e})=>e.spacing(1.75,3)};

		border-radius: ${({theme:e})=>e.shape.borderRadius}px;
		border: 1px solid ${({theme:e})=>e.palette.divider};
	}

	.MuiChip-root {
		font-weight: ${({theme:e})=>e.typography.fontWeightRegular};
	}
`,A=()=>{const{form:e}=(0,p.C)(),i=(0,k.useId)(),o=(0,b.VI)(e,e=>e.blockingMode,e=>i=>{i.blockingMode=e}),l=e.values.category===c.J.Necessary;return(0,k.useEffect)(()=>{l&&e.setValue(e=>{e.blockingMode=c.F.NeverBlock})},[l,e]),(0,f.jsxs)(_,{children:[(0,f.jsx)(u.A,{id:i,value:(0,v.__)("Blocking Mode","cookiez")}),(0,f.jsx)(y,{"aria-describedby":i,value:o.value,onChange:(e,i)=>o.onChange(i),children:j.tf.map(e=>{const{title:i,chip:o,description:n}=j.Gw[e],{color:t,Icon:s}=o,a=l&&e!==c.F.NeverBlock;return(0,f.jsx)(d.si,{value:e,disabled:a,label:(0,f.jsx)(h.A,{label:i,size:"small",color:t,variant:"standard",icon:(0,f.jsx)(s,{fontSize:"small"}),component:"span"}),description:(0,f.jsx)(m.A,{variant:"body2",color:"text.secondary",children:n})},e)})})]})};var C=o(50024),z=o(90196);const I=({onClose:e})=>{const{form:i,isLoading:o,scriptId:n,lockedCategory:t}=(0,p.C)();return(0,f.jsxs)(f.Fragment,{children:[(0,f.jsx)(a.A,{logo:!1,onClose:e,children:(0,f.jsx)(C.s,{children:n?(0,v.__)("Edit Script","cookiez"):(0,v.__)("Add Script","cookiez")})}),(0,f.jsxs)(z.q,{onSubmit:e=>{e.preventDefault(),i.submit()},children:[(0,f.jsxs)(z.h0,{dividers:!0,children:[(0,f.jsx)(z.MP,{children:(0,f.jsx)(d.YJ,{id:"name",label:(0,v.__)("Script Name","cookiez"),placeholder:(0,v.__)("Enter script name","cookiez")})}),(0,f.jsx)(z.MP,{children:(0,f.jsx)(d.YJ,{id:"value",label:(0,v.__)("Script Pattern / URL","cookiez"),placeholder:(0,v.__)("Enter URL or pattern","cookiez")})}),(0,f.jsx)(z.MP,{children:(0,f.jsx)(A,{})}),(0,f.jsx)(M,{in:i.values.blockingMode===c.F.BlockUntilConsent,unmountOnExit:!0,children:(0,f.jsxs)(z.MP,{children:[(0,f.jsx)(d.FI,{id:"category",label:(0,v.__)("Category","cookiez"),placeholder:(0,v.__)("Choose a category","cookiez"),options:j.bL.map(e=>({value:e,label:j.vK[e].title})),disabled:null!==t}),(0,f.jsx)("span",{})]})}),(0,f.jsx)(z.MP,{children:(0,f.jsx)(d.YJ,{id:"description",label:(0,v.__)("Description (optional)","cookiez"),placeholder:(0,v.__)("Brief script purpose","cookiez")})})]}),(0,f.jsxs)(s.A,{children:[(0,f.jsx)(l.A,{type:"button",variant:"text",color:"secondary",size:"medium",onClick:e,disabled:o,children:(0,v.__)("Close","cookiez")}),(0,f.jsx)(l.A,{type:"submit",variant:"contained",disabled:o,size:"medium",color:"primary",children:(0,v.__)("Save changes","cookiez")})]})]})]})},M=(0,r.I)(n.A)`
	&.MuiCollapse-entered {
		min-height: fit-content !important;
		overflow: visible;
	}
`,$=({scriptId:e,open:i,onClose:o,onSubmitOverride:l,initialValues:n,isExternalLoading:s,lockedCategory:a})=>(0,f.jsx)(t.A,{open:i,onClose:o,fullWidth:!0,maxWidth:"sm","aria-labelledby":"script-form-dialog-title",children:(0,f.jsx)(p.e,{scriptId:e,onClose:o,onSubmitOverride:l,initialValues:n,isExternalLoading:s,lockedCategory:a,children:(0,f.jsx)(I,{onClose:o})})})},50024(e,i,o){o.d(i,{n:()=>a,s:()=>s});var l=o(76656),n=o(23136),t=o(95231);const s=(0,t.I)(n.A)`
	font-weight: ${({theme:e})=>e.typography.fontWeightBold};
`,a=(0,t.I)(l.A)`
	padding: ${({theme:e})=>e.spacing(2,4)};
`}}]);