"use strict";(globalThis.webpackChunkcookiez=globalThis.webpackChunkcookiez||[]).push([[5411],{55076(e,i,o){o.d(i,{A:()=>p});var n=o(78048),t=o(93248),l=o(55984),s=o(85848),r=o(95231),a=o(10790);const d=(0,r.I)(n.A)`
	display: flex;
	flex-direction: column;
	align-items: flex-start;
`,c=(0,r.I)(s.A)`
	margin-block-end: ${({theme:e})=>e.spacing(1.5)};

	font-weight: ${({theme:e})=>e.typography.fontWeightBold};
`,p=({value:e,label:i,description:o,disabled:n})=>(0,a.jsx)(t.A,{value:e,disabled:n,control:(0,a.jsx)(l.A,{size:"small",color:"info",sx:{padding:0,marginInlineEnd:1}}),label:(0,a.jsxs)(d,{children:[(0,a.jsx)(c,{variant:"subtitle1",component:"p",children:i}),o]}),sx:{marginInline:0,alignItems:o?"flex-start":"center",...n&&{opacity:.5}}})},79729(e,i,o){o.d(i,{FI:()=>t.A,YJ:()=>s.A,si:()=>n.A,t5:()=>l.A});var n=o(55076),t=o(16775),l=o(37565),s=o(94494)},92587(e,i,o){o.r(i),o.d(i,{default:()=>J});var n=o(50602),t=o(77374),l=o(74456),s=o(34883),r=o(79729),a=o(36002),d=o(79011),c=o(50024),p=o(90196),u=o(86087),h=o(27723),x=o(78048),m=o(76992),b=o(63364),g=o(92182),j=o(95231),f=o(6766),k=o(52153),_=o(83355),v=o(43004),y=o(22863),A=o(10790);const z=(0,j.I)(x.A,{shouldForwardProp:e=>"$isSessionSelected"!==e})`
	width: 100%;

	display: flex;

	gap: ${({$isSessionSelected:e,theme:i})=>e?0:i.spacing(1)};

	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	input[type='number'] {
		-moz-appearance: textfield;
	}
`,I=()=>{const e="duration",i=(0,y.Ri)(e),[o,n]=(0,u.useState)(null),[t,l]=(0,u.useState)(null),s=(0,u.useRef)(!1);(0,u.useEffect)(()=>{if(s.current)return void(s.current=!1);if(-1===i.value)return n(null),void l(null);const e=(0,_.WV)(i.value);n(e.value),l(e.unit)},[i.value]);const{form:r}=(0,y.vc)(),a=r.validationErrors[e],d=(0,u.useId)(),c=(0,u.useId)(),x=(e,i)=>i===k.A.Session?null:null===i||null===e||e<0?0:(0,_.o0)(e,i),j=t===k.A.Session;return(0,A.jsxs)(v.A,{children:[(0,A.jsx)(p.q3,{htmlFor:j?c:d,children:(0,h.__)("Duration","cookiez")}),(0,A.jsxs)(z,{$isSessionSelected:j,children:[(0,A.jsx)(m.A,{in:!j,orientation:"horizontal",collapsedSize:0,sx:{flex:j?"0 0 0px":1,minWidth:0},children:(0,A.jsx)(p.H_,{id:d,fullWidth:!0,size:"small",color:"info",type:"number",placeholder:(0,h.__)("Number","cookiez"),value:o??"",onChange:o=>{const l=o.target.value?parseInt(o.target.value,10):null;n(l),s.current=!0;const a=x(l,t);i.onChange(a),r.validateField(e,a)},error:!!a})}),(0,A.jsxs)(p.Iu,{id:c,size:"small",color:"info",value:t??"",displayEmpty:!0,onChange:n=>{const t=n.target.value||null;l(t),s.current=!0;const a=x(o,t);i.onChange(a),r.validateField(e,a)},sx:{minWidth:120,flex:j?1:"0 0 120px"},error:!!a,children:[(0,A.jsx)(g.A,{disabled:!0,value:"",children:(0,h.__)("Units","cookiez")}),f.k.map(e=>(0,A.jsx)(g.A,{value:e,children:f.D[e].title},e))]})]}),a&&(0,A.jsx)(b.A,{error:!0,children:a})]})};var S=o(86162),C=o(52527),$=o(62481),w=o(73916),E=o(85848),W=o(15679);const F=(0,j.I)(x.A)`
	display: flex;
	flex-direction: column;
`,P=(0,j.I)(x.A)`
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: stretch;

	padding: ${({theme:e})=>e.spacing(1)};

	border: 1px solid ${({theme:e})=>e.palette.divider};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
`,D=(0,j.I)(x.A)`
	display: flex;
	flex-direction: column;

	gap: ${({theme:e})=>e.spacing(1)};
`,M=(0,j.I)(x.A)`
	display: flex;
	align-items: center;
	justify-content: space-between;

	gap: ${({theme:e})=>e.spacing(1)};
	padding: ${({theme:e})=>e.spacing(.5,1.5)};

	border: 1px solid ${({theme:e})=>e.palette.divider};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;

	:last-of-type {
		margin-block-end: ${({theme:e})=>e.spacing(1)};
	}

	.MuiTypography-root {
		font-weight: ${({theme:e})=>e.typography.fontWeightBold};
	}
`,R=(0,j.I)(x.A)`
	display: flex;
	justify-content: center;
	align-items: center;
`,T=()=>{const{entries:e,openAddScriptModal:i}=(0,a.v)(),o=(0,u.useId)();return(0,A.jsxs)(F,{children:[(0,A.jsx)(W.A,{value:(0,h.__)("Scripts (optional)","cookiez")}),(0,A.jsxs)(P,{id:o,children:[(0,A.jsx)(D,{role:"list",tabIndex:0,"aria-label":(0,h.__)("Scripts linked to the cookie","cookiez"),children:e.map(e=>{const i=(0,h.sprintf)(
// translators: %s: Script name.
// translators: %s: Script name.
(0,h.__)("Script %s","cookiez"),e.name),o=(0,h.sprintf)(
// translators: %s: Script label (e.g. "Script Analytics").
// translators: %s: Script label (e.g. "Script Analytics").
(0,h.__)("Delete %s","cookiez"),i),n=(0,h.sprintf)(
// translators: %s: Script label (e.g. "Script Analytics").
// translators: %s: Script label (e.g. "Script Analytics").
(0,h.__)("Edit %s","cookiez"),i);return(0,A.jsxs)(M,{role:"listitem",tabIndex:0,"aria-label":i,children:[(0,A.jsx)(E.A,{variant:"body2",children:e.name}),(0,A.jsxs)(x.A,{children:[(0,A.jsx)(w.A,{type:"button",size:"medium",onClick:e.onEdit,"aria-label":n,children:(0,A.jsx)(S.A,{fontSize:"small"})}),(0,A.jsx)(w.A,{type:"button",size:"medium",onClick:e.onRemove,"aria-label":o,children:(0,A.jsx)($.A,{fontSize:"small"})})]})]},e.key)})}),(0,A.jsx)(R,{children:(0,A.jsx)(n.A,{type:"button",variant:"text",size:"medium",color:"primary",startIcon:(0,A.jsx)(C.A,{fontSize:"tiny"}),onClick:i,children:(0,h.__)("Add Script","cookiez")})})]})]})},q=(0,u.lazy)(()=>Promise.all([o.e(6219),o.e(5864)]).then(o.bind(o,15613))),B=({onClose:e})=>{const{form:i,isLoading:o,scriptModal:t,editingPersistedScriptId:x,editingScriptInitialValues:m,submitScriptForm:b}=(0,a.v)();return(0,A.jsxs)(A.Fragment,{children:[(0,A.jsx)(s.A,{logo:!1,onClose:e,children:(0,A.jsx)(c.s,{children:(0,h.__)("Add Cookie","cookiez")})}),(0,A.jsxs)(p.q,{onSubmit:e=>{e.preventDefault(),i.submit()},children:[(0,A.jsxs)(p.h0,{dividers:!0,children:[(0,A.jsxs)(p.MP,{children:[(0,A.jsx)(r.YJ,{id:"name",label:(0,h.__)("Cookie ID","cookiez"),placeholder:(0,h.__)("Enter cookie name"),title:(0,h.__)("The unique identifier for the cookie as stored in the browser.","cookiez")}),(0,A.jsx)(I,{})]}),(0,A.jsxs)(p.MP,{children:[(0,A.jsx)(r.YJ,{id:"domain",label:(0,h.__)("Domain (optional)","cookiez"),placeholder:(0,h.__)("Enter domain","cookiez"),title:(0,h.__)("The domain where the cookie is set.","cookiez")}),(0,A.jsx)(r.FI,{id:"category",label:(0,h.__)("Category","cookiez"),placeholder:(0,h.__)("Choose a category","cookiez"),options:d.bL.map(e=>({value:e,label:d.vK[e].title}))})]}),(0,A.jsx)(p.MP,{children:(0,A.jsx)(T,{})}),(0,A.jsx)(p.MP,{children:(0,A.jsx)(r.t5,{id:"description",label:(0,h.__)("Description (optional)","cookiez"),rows:5})})]}),(0,A.jsxs)(l.A,{children:[(0,A.jsx)(n.A,{type:"button",variant:"text",color:"secondary",size:"medium",onClick:e,disabled:o,children:(0,h.__)("Close","cookiez")}),(0,A.jsx)(n.A,{type:"submit",variant:"contained",disabled:o,size:"medium",color:"primary",children:(0,h.__)("Save","cookiez")})]})]}),t.isOpen&&(0,A.jsx)(u.Suspense,{fallback:null,children:(0,A.jsx)(q,{open:t.isOpen,onClose:t.close,scriptId:x,initialValues:m,onSubmitOverride:b,isExternalLoading:o,lockedCategory:i.values.category||null})})]})},J=({cookieId:e,open:i,onClose:o})=>(0,A.jsx)(t.A,{open:i,onClose:o,fullWidth:!0,maxWidth:"sm","aria-labelledby":"cookie-form-dialog-title",children:(0,A.jsx)(a.P,{cookieId:e,children:(0,A.jsx)(B,{onClose:o})})})},50024(e,i,o){o.d(i,{n:()=>r,s:()=>s});var n=o(76656),t=o(23136),l=o(95231);const s=(0,l.I)(t.A)`
	font-weight: ${({theme:e})=>e.typography.fontWeightBold};
`,r=(0,l.I)(n.A)`
	padding: ${({theme:e})=>e.spacing(2,4)};
`}}]);