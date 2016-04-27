/*
 * Copyright © Aleksey Nemiro, 2016. All rights reserved.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
module SmallServerAdmin.Models {

	/**
	 * Represents a process.
	 */
	export class Process {

    /**
     * The process ID.
     */
    public PID: number;

    /**
     * The process parent ID.
     */
    public PPID: number;

    /**
     * The process name.
     */
    public Name: string;

    /**
     * The owner name of the process.
     */
    public Username: string;

    /**
     * The percent of CPU usage.
     */
    public CPU: number;

    /**
     * The percent of RAM usage.
     */
    public Memory: number;

    /**
     * The parameter string of the process.
     */
    public Command: string;

    public StartTime: string;

    public ElapsedTime: string;

    /**
     * Virtual set size (Kb).
     */
    public VSZ: number;

    /**
     * Resident set size (Kb).
     */
    public RSS: number;
    
    /**
     * The status of the process.
     */
    public Status: string;

		/**
		 * Loading/updating indicator.
		 */
		public Loading: boolean;
 	
	}

}