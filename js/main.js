const nameProperty = 'name';
const genderProperty = 'gender';
const statusProperty = 'status';

const theme = {
    colors: {
        maleBadgeBackground: '#A8D07A',
        femaleBadgeText: '#A8D07A',
        maleBadgeText: '#000000',
        princePrincessBorder: '#343434',
        civilianBorder: '#58ADA7',
        personText: '#383838',
        personNodeBackground: 'rgba(168,208,122,0.25)',
        selectionStroke: '#383838',
        counterBackground: '#383838',
        counterBorder: '#383838',
        counterText: '#383838',
        link: '#383838'
    },
    fonts: {
        badgeFont: 'bold 8px Montserrat',
        birthDeathFont: '10px Montserrat',
        nameFont: '700 12px Montserrat',
    }
};
const onMouseEnterPart = (e, part) => part.isHighlighted = true;
const onMouseLeavePart = (e, part) => {
    if (!part.isSelected) part.isHighlighted = false;
}
const onSelectionChange = (part) => {
    part.isHighlighted = part.isSelected;
}
const STROKE_WIDTH = 1;
const CORNER_ROUNDNESS = 13;

function strokeStyle(shape) {
    return shape
        .set({
            fill: theme.colors.personNodeBackground,
            strokeWidth: STROKE_WIDTH
        })
        .bind('stroke', statusProperty,  theme.colors.princePrincessBorder)
        .bind('fill', theme.colors.princeBackgrount)
}
const genderToText = (gender) => (gender === 'M' ? 'Ақпарат' : 'Ақпарат');
const genderToTextColor = (gender) =>
    gender === 'M' ? theme.colors.maleBadgeText : theme.colors.femaleBadgeText;
const genderToFillColor = (gender) =>
    gender === 'M' ? theme.colors.maleBadgeBackground : theme.colors.femaleBadgeBackground;
const personBadge = () =>
    new go.Panel('Auto', {alignmentFocus: go.Spot.TopRight, alignment: new go.Spot(1, 0, -25, STROKE_WIDTH - 0.5)})
        .add(new go.Shape({figure: 'RoundedRectangle', parameter1: CORNER_ROUNDNESS,
            parameter2: 4 | 8,
            desiredSize: new go.Size(NaN, 22.5),
            stroke: null
        }).bind('fill', genderProperty, genderToFillColor),
            new go.TextBlock({
            font: theme.fonts.badgeFont
        }).bind('stroke', genderProperty, genderToTextColor).bind('text', genderProperty, genderToText)
    )
const personBirthDeathTextBlock = () =>
    new go.TextBlock({
        stroke: theme.colors.personText,
        font: theme.fonts.birthDeathFont,
        alignmentFocus: go.Spot.Top,
        alignment: new go.Spot(0.5, 1.12, 0, -35)
    }).bind('text', '', ({born, death}) => {
        if (!born) return ''; return `${born} - ${death ?? ''}`;
    })
const personMainShape = () =>
    new go.Shape({
        figure: 'RoundedRectangle',
        desiredSize: new go.Size(150, 65),
        portId: '',
        parameter1: CORNER_ROUNDNESS
    }).apply(strokeStyle);

const personNameTextBlock = () =>
    new go.TextBlock({
        stroke: theme.colors.personText,
        font: theme.fonts.nameFont,
        desiredSize: new go.Size(145, 55),
        overflow: go.TextOverflow.Ellipsis,
        textAlign: 'center',
        verticalAlignment: go.Spot.Center,
        toolTip: go.GraphObject.build('ToolTip').add(new go.TextBlock({margin: 4}).bind('text', nameProperty)),
        alignmentFocus: go.Spot.Top,
        alignment: new go.Spot(0.5, -0.29, 0, 25)
    }).bind('text', nameProperty)
const createNodeTemplate = () =>
    new go.Node('Spot', {
        selectionAdorned: false,
        mouseEnter: onMouseEnterPart,
        mouseLeave: onMouseLeavePart,
        selectionChanged: onSelectionChange,
        movable: false,
        click: async (e, node) => {
            if (!e.handled) {
                if (node.isTreeExpanded) {
                    // Скрываем детей
                    diagram.commandHandler.collapseTree(node);
                } else {
                    // Загружаем данные и разворачиваем узел только после успешного добавления новых данных
                    await fetchAndAddFamilyData(node.data.id);
                    diagram.updateAllTargetBindings();  // Обновляем диаграмму
                    diagram.commandHandler.expandTree(node);
                }
            }
        }
    }).add(new go.Panel('Spot').add(personMainShape(), personNameTextBlock(), personBirthDeathTextBlock()),
        personBadge().set({
            click: (e, obj) => {
                console.log(`Клик по значку: ${obj.part.data.id}`);
                e.handled = true;
            }
        }),
    );

// В fetchAndAddFamilyData добавим await в return, чтобы click ждал окончания
async function fetchAndAddFamilyData(id) {
    try {
        const response = await fetch(`http://api.atatek.com/tree/get_items.php?id=${id}`);
        const result = await response.json();

        if (result.status) {
            const existingIds = new Set(familyData.map(member => member.id));
            const newMembers = result.data
                .map(member => ({
                    id: parseInt(member.id),
                    name: member.name,
                    gender: 'M',
                    status: 'status',
                    born: member.birth_year,
                    death: member.death_year,
                    parent: parseInt(member.parent_id)
                }))
                .filter(member => !existingIds.has(member.id));

            if (newMembers.length > 0) {
                familyData.push(...newMembers);
                diagram.model.addNodeDataCollection(newMembers);
            }
        } else {
            console.error('Error in API response');
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

const createLinkTemplate = () =>
    new go.Link({
        selectionAdorned: false,
        routing: go.Routing.Orthogonal,
        layerName: 'Background',
        mouseEnter: onMouseEnterPart,
        mouseLeave: onMouseLeavePart
    })
        .add(new go.Shape({
                stroke: theme.colors.link,
                strokeWidth: 1
            })
        );
let diagram;
const initDiagram = (divId) => {
    diagram = new go.Diagram(divId, {
        layout: new go.TreeLayout({
            angle: 90,
            nodeSpacing: 20,
            layerSpacing: 50,
            layerStyle: go.TreeLayout.LayerUniform,
            treeStyle: go.TreeStyle.LastParents,
            alternateAngle: 90,
            alternateLayerSpacing: 35,
            alternateAlignment: go.TreeAlignment.BottomRightBus,
            alternateNodeSpacing: 20
        }),
        'toolManager.hoverDelay': 100,
        linkTemplate: createLinkTemplate(),
        model: new go.TreeModel({
            nodeKeyProperty: 'id'
        })
    });

    diagram.nodeTemplate = createNodeTemplate();
    diagram.model.nodeDataArray = familyData;

    diagram.addDiagramListener('InitialLayoutCompleted', () => {
        const root = diagram.findNodeForKey(14);
        if (!root) return;
        diagram.scale = 0.7;
        diagram.scrollToRect(root.actualBounds);
    });

};
const familyData = [
    { id: 14, name: 'Алаш', gender: 'M', status: 'king', born: null, death: null,},
    { id: 1, name: 'Ұлы жүз', gender: 'M', status: 'king', born: null, death: null, parent: 14 },
    { id: 2, name: 'Орта жүз', gender: 'M', status: 'king', born: null, death: null, parent: 14 },
    { id: 3, name: 'Кіші жүз', gender: 'M', status: 'king', born: null, death: null, parent: 14 },
    { id: 4, name: 'Жүзден тыс', gender: 'M', status: 'king', born: null, death: null, parent: 14 },

];
window.addEventListener('DOMContentLoaded', () => {
    initDiagram('myDiagramDiv');
    setTimeout(() => {

    }, 300);
});

