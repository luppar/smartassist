// Clear LEDs

// System calls
#include <unistd.h>
// Input/output streams and functions
#include <iostream>
// Interfaces with Everloop
#include "header_files/everloop.h"
// Holds data for Everloop
#include "header_files/everloop_image.h"
// Communicates with MATRIX device
#include "header_files/matrixio_bus.h"


// Handling namespace issues
namespace hal = matrix_hal;

int main() {
    
    // Create MatrixIOBus object for hardware communication
    matrix_hal::MatrixIOBus bus;
    // Initialize bus and exit program if error occurs
    if (!bus.Init()) return false;

    // Holds the number of LEDs on MATRIX device
    int ledCount = bus.MatrixLeds();
    // Create EverloopImage object, with size of ledCount
    matrix_hal::EverloopImage everloop_image(ledCount);
    // Create Everloop object
    matrix_hal::Everloop everloop;
    // Set everloop to use MatrixIOBus bus
    everloop.Setup(&bus);

    // reset leds
    for (matrix_hal::LedValue &led : everloop_image.leds) {led.red = 0; led.green = 0;led.blue = 0;led.white = 0;} everloop.Write(&everloop_image);

    return 0;

}


