wp.domReady( function() {

    /**
     * Buttons block variations
     */
    wp.blocks.registerBlockVariation( 'core/button', {
        name: 'maxboxy-closer',
        title: 'MaxBoxy Panel Closer',
        description: 'Close the MaxBoxy panel',
        category: 'widgets',
        isDefault: false,
        attributes: {
            className: 'mboxy-closer',
        },
        scope: 'transform',
        isActive: [ 'className' ],
    } );

    wp.blocks.registerBlockVariation( 'core/button', {
        name: 'maxboxy-toggler',
        title: 'MaxBoxy Panel Toggler',
        description: 'Toggle the MaxBoxy panel',
        category: 'widgets',
        isDefault: false,
        attributes: {
            className: 'mboxy-toggler',
        },
        scope: 'transform',
        isActive: [ 'className' ],
    } );

    wp.blocks.registerBlockVariation( 'core/button', {
        name: 'maxboxy-trigger',
        title: 'MaxBoxy Panel Trigger',
        description: 'Trigger the MaxBoxy panel from the content element (Requiers Pro Version)',
        category: 'widgets',
        isDefault: false,
        attributes: {
            className: 'mboxy-trigger',
        },
        scope: 'transform',
        isActive: [ 'className' ],
    } );

    wp.blocks.registerBlockVariation( 'core/button', {
        name: 'maxboxy-drifter',
        title: 'MaxBoxy Panel Drifter',
        description: 'Drift the MaxBoxy panel from the content element (Requiers Pro Version)',
        category: 'widgets',
        isDefault: false,
        attributes: {
            className: 'mboxy-drifter',
        },
        scope: 'transform',
        isActive: [ 'className' ],
    } );

} ); // end domReady
