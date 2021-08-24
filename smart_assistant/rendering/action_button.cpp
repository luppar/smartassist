// Button Actions
//
// Should be started at boot time.
//

// System calls
#include <unistd.h>
// Input/output streams and functions
#include <iostream>

// Allows usage of GPIO Pins 
#include "header_files/gpio_control.h"
// Communicates with MATRIX device
#include "header_files/matrixio_bus.h"

// Handling namespace issues
namespace hal = matrix_hal;

#define CLK_FRQ 200000000

int main() {
    
    // Create MatrixIOBus object for hardware communication
    hal::MatrixIOBus bus;
    // Initialize bus and exit program if error occurs
    if (!bus.Init()) return false;

    // Creates GPIOControl object to enable communication with the pins
    hal::GPIOControl gpio;
    gpio.Setup(&bus);

    // Sets pin 0 to I/O mode
    gpio.SetFunction(0, 0);

    // Sets pin 0 to input
    gpio.SetMode(0, 0);

    // Pin variable
    bool read_data0;

    // Loop
    while (true) {
        
        // Check for signal on pin 0
        read_data0 = gpio.GetGPIOValue(0);

        // In case the button is pressed - do whatever is written in here
        if (read_data0 == 1) {

            // DEBUG
            // std::cout << "BUTTON PRESSED! " << std::endl;
            
            // Stop all rendering actions and clear LEDs
            system("killall render");
            system("./clear");
            
            // REBOOT SYSTEM after 1 minute
            // system("sudo /sbin/shutdown -r 1");


            return 0;

        }
        
        usleep(100000);
    }

    return 0;
}
