SOURCE_render = render.cpp
OUTPUT_render = render

SOURCE_clear = clear.cpp
OUTPUT_clear = clear

SOURCE_action = action_button.cpp
OUTPUT_action = action_button

FLAGS = -std=c++11 -lmatrix_creator_hal -lgflags
CC = g++


all:
	$(CC) -o $(OUTPUT_render) $(SOURCE_render) $(FLAGS)
	$(CC) -o $(OUTPUT_clear) $(SOURCE_clear) $(FLAGS)
	$(CC) -o $(OUTPUT_action) $(SOURCE_action) $(FLAGS)