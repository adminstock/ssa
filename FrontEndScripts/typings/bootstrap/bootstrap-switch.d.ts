// Type definitions for bootstrap-switch v3.3.2
// Project: http://www.bootstrap-switch.org
// Definitions by: Aleksey Nemiro <https://github.com/alekseynemiro>

/** 
 * Interface for the bootstrap-switch settings.
 */
interface BootstrapSwitchSettings {

	/** 
	 * The checkbox state. 
	 */
	state?: boolean;

	/** 
	 * The checkbox size: 'mini', 'small', 'normal', 'large'.
	 */
	size?: string;	

	/** 
	 * Animate the switch. 
	 */
	animate?: boolean;

	/** 
	 * Disable state. 
	 */
	disabled?: boolean;

	/** 
	 * Readonly state. 
	 */
	readonly?: boolean;

	/** 
	 * Indeterminate. 
	 */
	indeterminate?: boolean;

	/** 
	 * Inverse switch direction. 
	 */
	inverse?: boolean;

	/** 
	 * Allow this radio button to be unchecked by the user. 
	 */
	radioAllOff?: boolean;

	/** 
	 * Color of the left side of the switch: 'primary', 'info', 'success', 'warning', 'danger', 'default'. 
	 */
	onColor?: string;

	/** 
	 * Color of the right side of the switch: 'primary', 'info', 'success', 'warning', 'danger', 'default'. 
	 */
	offColor?: string;

	/** 
	 * Text of the left side of the switch. 
	 */
	onText?: string;

	/** 
	 * Text of the right side of the switch. 
	 */
	offText?: string;

	/** 
	 * Text of the center handle of the switch. 
	 */
	labelText?: string;

	/**
	 * Width of the left and right sides in pixels. 
	 */
	handleWidth?: string|number;

	/** 
	 * Width of the center handle in pixels. 
	 */
	labelWidth?: string|number;

	/** 
	 * Global class prefix. 
	 */
	baseClass?: string;

	/** 
	 * Container element class(es). 
	 */
	wrapperClass?: string;

	/** 
	 * Callback function to execute on initialization. 
	 * 
	 * @param state The checkbox state.
	 */
	onInit?: (event: JQueryEventObject, state: boolean) => void;

	/** 
	 * Callback function to execute on switch state change. 
	 * 
	 * @param state The checkbox state.
	 */
	onSwitchChange?: (event: JQueryEventObject, state: boolean) => void;

}

interface BootstrapSwitch {

	/** 
	 * The default settings. 
	 */
	defaults: BootstrapSwitchSettings;

}

interface JQueryStatic {

	bootstrapSwitch: BootstrapSwitch;

}

interface JQuery {

	/**
	 * Initializes Bootstrap Switch.
	 */
	bootstrapSwitch(options?: BootstrapSwitchSettings): JQuery;
	
} 