// Type definitions for bootstrap-spinedit v1.0.0
// Project: https://github.com/scyv/bootstrap-spinedit
// Definitions by: Aleksey Nemiro <https://github.com/alekseynemiro>


interface BootstrapSpinedit {

}

interface JQueryStatic {

  spinedit: BootstrapSpinedit;

}

interface JQuery {

  /**
   * Initializes Bootstrap Spinedit.
   */
  spinedit(options?: any): JQuery;

} 